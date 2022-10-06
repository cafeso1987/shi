<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Services\BitCoinApiService;
use App\Http\Services\Logger;
use App\Http\Services\WalletService;
use App\Model\BuyCoinHistory;
use App\Model\DepositeTransaction;
use App\Model\Wallet;
use App\Model\WalletAddressHistory;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Pusher\Pusher;
use Pusher\PusherException;

class WalletNotifier extends Controller
{

    private $logger;
    private $service;
    function __construct()
    {
        $this->logger = new Logger();
        $this->service = new WalletService();
    }
    // Wallet notifier for checking and confirming order process
    public function coinPaymentNotifier(Request $request)
    {
        Log::info('payment notifier called');
        $raw_request = $request->all();
        Log::info(json_encode($raw_request));
        $merchant_id = settings('ipn_merchant_id');
        $secret = settings('ipn_secret');

        Log::info('merchant_id =>'.$merchant_id);
        Log::info('ipn_secret =>'.$secret);

        if (env('APP_ENV') != "local"){
            if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) {
                Log::info('No HMAC signature sent');

                die("No HMAC signature sent");
            }

            $merchant = isset($_POST['merchant']) ? $_POST['merchant']:'';
            if (empty($merchant)) {
                Log::info('No Merchant ID passed');

                die("No Merchant ID passed");
            }

            if ($merchant != $merchant_id) {
                Log::info('Invalid Merchant ID');

                die("Invalid Merchant ID");
            }

            $request = file_get_contents('php://input');
            if ($request === FALSE || empty($request)) {
                Log::info('Error reading POST data');

                die("Error reading POST data");
            }

            $hmac = hash_hmac("sha512", $request, $secret);

            if ($hmac != $_SERVER['HTTP_HMAC']) {
                Log::info('HMAC signature does not match');

                die("HMAC signature does not match");
            }
        }

        return $this->depositeWallet($raw_request);
    }

    public function depositeWallet($request)
    {
        Log::info('call deposit wallet');
        $data = ['success'=>false,'message'=>'something went wrong'];

        DB::beginTransaction();
        try {
            $request = (object)$request;
            Log::info(json_encode($request));

            $walletAddress = WalletAddressHistory::where(['address'=> $request->address])->with('wallet')->first();

            if (isset($walletAddress)) {
                if (($request->ipn_type == "deposit") && ($request->status >= 100)) {
                    $wallet =  $walletAddress->wallet;
                    $data['user_id'] = $wallet->user_id;
                    if (!empty($wallet)){
                        $checkDeposit = DepositeTransaction::where('transaction_id', $request->txn_id)->first();
                        if (isset($checkDeposit)) {
                            $data = ['success'=>false,'message'=>'Transaction id already exists in deposit'];
                            Log::info('Transaction id already exists in deposit');
                            return $data;
                        }

                        $depositData = [
                            'address' => $request->address,
                            'address_type' => ADDRESS_TYPE_EXTERNAL,
                            'amount' => $request->amount,
                            'fees' => 0,
                            'coin_type' => $walletAddress->coin_type,
                            'transaction_id' => $request->txn_id,
                            'confirmations' => $request->confirms,
                            'status' => STATUS_SUCCESS,
                            'receiver_wallet_id' => $wallet->id
                        ];

                        $depositCreate = DepositeTransaction::create($depositData);
                        Log::info(json_encode($depositCreate));

                        if (($depositCreate)) {
                            Log::info('Balance before deposit '.$wallet->balance);
                            $wallet->increment('balance', $depositCreate->amount);
                            Log::info('Balance after deposit '.$wallet->balance);
                            $data['message'] = 'Deposit successfully';
                            $data['success'] = true;
                        } else {
                            Log::info('Deposit not created ');
                            $data['message'] = 'Deposit not created';
                            $data['success'] = false;
                        }

                    } else {
                        $data = ['success'=>false,'message'=>'No wallet found'];
                        Log::info('No wallet found');
                    }
                }
            } else {
                $data = ['success'=>false,'message'=>'Wallet address not found'];
                Log::info('Wallet address not found id db');
            }

            DB::commit();
            return $data;
        } catch (\Exception $e) {
            $data['message'] = $e->getMessage().' '.$e->getLine();
            Log::info($data['message']);
            DB::rollback();

            return $data;
        }
    }

 // wallet notifier for personal node

    public function walletNotify(Request $request)
    {
        Log::info('notify called');
        Log::info(json_encode($request->all()));
        $coinType = strtoupper($request->coin_type);

        $transactionId = $request->transaction_id;
        Log::info('transactionId : '. $transactionId);
        $coinservice =  new BitCoinApiService(settings('coin_api_user'), settings('coin_api_pass'),settings('coin_api_host'), settings('coin_api_port'));
        $transaction = $coinservice->getTranscation($transactionId);

        if($transaction) {
            $details = $transaction['details'];

            foreach ($details as $data) {
                if ($data['category'] = 'receive') {
                    $address[] = $data['address'];
                    $amount[] = $data['amount'];
                }
            }
            if (empty($address) || empty($amount)) {
                Log::info('transaction : This is a withdraw transaction hash ');
                return response()->json(['message' => __('This is a withdraw transaction hash')]);
            }
            DB::beginTransaction();
            try {
                $wallets = WalletAddressHistory::whereIn('address', $address)->get();

                if ($wallets->isEmpty()) {
                    Log::info('transaction address : Notify Unsuccessful. Address not found ');
                    return response()->json(['message' => __('Notify Unsuccessful. Address not found!')]);
                }
                if (!$wallets->isEmpty()) {
                    foreach ($wallets as $wallet) {
                        foreach ($address as $key => $val) {
                            if ($wallet->address == $val) {
                                $currentAmount = $amount[$key];
                            }
                        }
                        $inserts [] = [
                            'address' => $wallet->address,
                            'receiver_wallet_id' => $wallet->wallet_id,
                            'address_type' => 1,
                            'amount' => $currentAmount,
                            'coin_type' => $coinType,
//                            'type' => 'receive',
                            'status' => STATUS_PENDING,
                            'transaction_id' => $transactionId,
                            'confirmations' => $transaction['confirmations'],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
                    }
                }

                $response = [];
                if (!empty($inserts)) {
                    foreach ($inserts as $insert) {
                        $has_transaction = DepositeTransaction::where(['transaction_id' => $insert['transaction_id'], 'address' => $insert['address']])->count();
                        if (!$has_transaction) {
                            try {
                                DepositeTransaction::insert($insert);
                            } catch (\Exception $e) {
                                return response()->json([
                                    'message' => __('Transaction Hash is already in DB .'.$e->getMessage()),
                                ]);
                            }
                            $response[] = [
                                'transaction_id' => $insert['transaction_id'],
                                'address' => $insert['address'],
                                'success' => true
                            ];
                        } else {
                            $response [] = [
                                'transaction_id' => $insert['transaction_id'],
                                'address' => $insert['address'],
                                'success' => false
                            ];
                        }
                    }
                }
                Log::info('notyfy- ');
                Log::info($response);
                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();
                $response [] = [
                    'transaction_id' => '',
                    'address' => '',
                    'success' => false
                ];
            }

            if (empty($response)) {
                return response()->json([
                    'message' => __('Notified Unsuccessful.'),
                ]);
            }

            return response()->json([
                'response' => $response,
            ]);
        }

        return response()->json(['message' => __('Not a valid transaction.')]);
    }

    public function notifyConfirm(Request $request)
    {
        Log::info('notify confirmed called');
        Log::info(json_encode($request->all()));
        $number_of_confirmation = settings('number_of_confirmation');
        $transactions = $request->transactions['transactions'];
        Log::info(json_encode($transactions));

        if(!empty($transactions))
        {
            foreach ($transactions as $transaction)
            {
                if($transaction['category'] == 'receive')
                {
                    $is_confirmed = false;
                    $transactionId = $transaction['txid'];
                    $address = $transaction['address'];
                    $pendingTransaction = DepositeTransaction::where(['transaction_id' => $transactionId, 'address' => $address])->first();
                    if(!empty($pendingTransaction))
                    {
                        $confirmation = $transaction['confirmations'];
                        Log::info('confirmation-> '.$confirmation);
                        if($confirmation >= $number_of_confirmation && $pendingTransaction->status != STATUS_SUCCESS)
                        {
                            DB::beginTransaction();

                            try {
                                $amount = $pendingTransaction->amount;
                                Log::info('Wallet-Notify');
                                Log::info('Received Amount: '. $amount);
                                Log::info('Balance Before Update: '. $pendingTransaction->receiverWallet->balance);
                                $pendingTransaction->receiverWallet->increment('balance', $amount);
                                Log::info('Balance After Update: '. $pendingTransaction->receiverWallet->balance);
                                $update = DepositeTransaction::where(['id' => $pendingTransaction->id, 'status' => STATUS_PENDING])->update(['confirmations' => $confirmation, 'status' => STATUS_SUCCESS]);
                                Log::info('Wallet-Notify executed');
                                if (!$update) {
                                    DB::rollback();
                                    $response[] = [
                                        'txid' => $transactionId,
                                        'is_confirmed' => false,
                                        'message' => __('Already deposited.')
                                    ];

                                    $logText = [
                                        'walletID' => $pendingTransaction->receiverWallet->id,
                                        'transactionID' => $transactionId,
                                        'amount' => $amount,
                                    ];
                                    Log::info('Wallet-Notify-Failed');
                                    Log::info(json_encode($logText));

                                    return response()->json($response);
                                }
                            } catch (\Exception $e) {
                                DB::rollback();
                                $response[] = [
                                    'txid' => $transactionId,
                                    'is_confirmed' => false,
                                    'message' => __('Already deposited.')
                                ];

                                $logText = [
                                    'walletID' => $pendingTransaction->receiverWallet->id,
                                    'transactionID' => $transactionId,
                                    'amount' => $amount,
                                ];
                                Log::info('Wallet-Notify-Failed');
                                Log::info(json_encode($logText));
                                Log::info($e->getMessage());

                                return response()->json($response);
                            }
                            DB::commit();

                            $is_confirmed = true;
                            $response[] = [
                                'txid' => $transactionId,
                                'is_confirmed' => $is_confirmed,
                                'current_confirmation' => $confirmation
                            ];
                        }
                        else
                        {
                            if($confirmation >= $number_of_confirmation && $pendingTransaction->status == STATUS_SUCCESS)
                            {
                                $pendingTransaction->update(['confirmations' => $confirmation]);
                            }
                            elseif ($confirmation < $number_of_confirmation && $pendingTransaction->status == STATUS_PENDING)
                            {
                                $pendingTransaction->update(['confirmations' => $confirmation]);
                            }

                            $response[] = [
                                'txid' => $transactionId,
                                'is_confirmed' => $is_confirmed,
                                'current_confirmation' => $confirmation
                            ];
                        }
                    }
                    else
                    {
                        $response[] = [
                            'txid' => $transactionId,
                            'is_confirmed' => $is_confirmed,
                            'message' => __('Transaction Id is not available')
                        ];
                    }
                }
            }
        }
        else{
            Log::info('No Transaction Found');
            $response [] = [
                'message' => __('No Transaction Found')
            ];
        }

        if (!isset($response)) {
            return response()->json(['status' => false]);
        }

        return response()->json($response);
    }


    /**
     * For broadcast data
     * @param $data
     */
    public function broadCast($data)
    {
        $channelName = 'depositConfirmation.' . customEncrypt($data['userId']);
        $fields = json_encode([
            'channel_name' => $channelName,
            'event_name' => 'confirm',
            'broadcast_data' => $data['broadcastData'],
        ]);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://' . env('BROADCAST_HOST') . '/api/broadcast',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                'broadcast-secret: an9$md_eoUqmNpa@bm34Jd'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
    }

    // bitgo wallet webhook
    public function bitgoWalletWebhook(Request $request)
    {
        Log::info('bitgoWalletWebhook start');
        try {
            $this->logger->log('bitgoWalletWebhook',' bitgoWalletWebhook called');
            $this->logger->log('bitgoWalletWebhook $request',json_encode($request->all()));

            if (isset($request->hash)) {
                $txId = $request->hash;
                $type = $request->type;
                $coinType = $request->coin;
                $state = $request->state;
                $walletId = $request->wallet;
                $this->logger->log('bitgoWalletWebhook hash', $txId);
                if ($type == 'transfer' && $state == 'confirmed') {
                    $checkHashInDB = DepositeTransaction::where(['transaction_id' => $txId, 'coin_type' => $coinType])->first();
                    if (isset($checkHashInDB)) {
                        $this->logger->log('bitgoWalletWebhook, already deposited hash -> ',$txId);
                    } else {
                        $this->logger->log('bitgoWalletCoinDeposit', 'called -> ');
                        $this->service->bitgoWalletCoinDeposit($coinType,$walletId,$txId);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->logger->log('bitgoWalletWebhook', $e->getMessage());
        }

    }
}
