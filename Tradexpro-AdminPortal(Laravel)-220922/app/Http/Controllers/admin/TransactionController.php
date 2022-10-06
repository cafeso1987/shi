<?php

namespace App\Http\Controllers\admin;

use App\Http\Repositories\AffiliateRepository;
use App\Http\Services\BitCoinApiService;
use App\Http\Services\CoinPaymentsAPI;
use App\Http\Services\Logger;
use App\Http\Services\TransService;
use App\Model\DepositeTransaction;
use App\Model\Wallet;
use App\Model\WithdrawHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function foo\func;

class TransactionController extends Controller
{
    public $affiliateRepo;
    public $coinPayment;
    public $logger;
    public $bitCoinApi;
    public $transService;

    public function __construct()
    {
        $this->affiliateRepo = new AffiliateRepository();
        $this->affiliateRepo = new AffiliateRepository();
        $this->coinPayment = new CoinPaymentsAPI();
        $this->logger = new Logger();
        $this->bitCoinApi =  new BitCoinApiService(settings('coin_api_user'),settings('coin_api_pass'),settings('coin_api_host'),settings('coin_api_port'));
        $this->transService = new TransService();
    }


    // deposit  history
    public function adminTransactionHistory(Request $request)
    {
        $data['title'] = __('Transaction History');
        if ($request->ajax()) {
            $deposit = DepositeTransaction::select('deposite_transactions.address'
                , 'deposite_transactions.amount'
                , 'deposite_transactions.fees'
                , 'deposite_transactions.transaction_id'
                , 'deposite_transactions.confirmations'
                , 'deposite_transactions.address_type as addr_type'
                , 'deposite_transactions.created_at'
                , 'deposite_transactions.sender_wallet_id'
                , 'deposite_transactions.receiver_wallet_id'
                , 'deposite_transactions.status'
                , 'deposite_transactions.coin_type'
            )->orderBy('deposite_transactions.id', 'desc');

            return datatables()->of($deposit)
                ->addColumn('address_type', function ($dpst) {
                    if ($dpst->addr_type == 'internal_address') {
                        return __('External');
                    } else {
                        return addressType($dpst->addr_type);
                    }

                })
                ->addColumn('coin_type', function ($dpst) {
                    return $dpst->coin_type;
                })
                ->addColumn('status', function ($dpst) {
                    return deposit_status($dpst->status);
                })
                ->addColumn('sender', function ($dpst) {
                    if (!empty($dpst->senderWallet) && $dpst->senderWallet->type == CO_WALLET) return  'Multi-signature Pocket: '.$dpst->senderWallet->name;
                    else
                        return isset($dpst->senderWallet->user) ? $dpst->senderWallet->user->first_name . ' ' . $dpst->senderWallet->user->last_name : 'N/A';
                })
                ->addColumn('receiver', function ($dpst) {
                    if (!empty($dpst->receiverWallet) && $dpst->receiverWallet->type == CO_WALLET) return  'Multi-signature Pocket: '.$dpst->receiverWallet->name;
                    else
                        return isset($dpst->receiverWallet->user) ? $dpst->receiverWallet->user->first_name . ' ' . $dpst->receiverWallet->user->last_name : 'N/A';
                })
                ->make(true);
        }

        return view('admin.transaction.all-transaction', $data);
    }

    // withdrawal history
    public function adminWithdrawalHistory(Request $request)
    {
        if ($request->ajax()) {
            $withdrawal = WithdrawHistory::select('withdraw_histories.address'
                    , 'withdraw_histories.amount'
                    , 'withdraw_histories.user_id'
                    , 'withdraw_histories.fees'
                    , 'withdraw_histories.transaction_hash'
                    , 'withdraw_histories.confirmations'
                    , 'withdraw_histories.address_type as addr_type'
                    , 'withdraw_histories.created_at'
                    , 'withdraw_histories.wallet_id'
                    , 'withdraw_histories.coin_type'
                    , 'withdraw_histories.receiver_wallet_id'
                    , 'withdraw_histories.status'
                )->orderBy('withdraw_histories.id', 'desc');
            return datatables()->of($withdrawal)
                ->addColumn('address_type', function ($wdrl) {
                    return addressType($wdrl->addr_type);
                })
                ->addColumn('coin_type', function ($wdrl) {
                    return find_coin_type($wdrl->coin_type);
                })
                ->addColumn('sender', function ($wdrl) {
                    if(!empty($wdrl->user)) $user = $wdrl->user;
                    else $user = isset($wdrl->senderWallet) ? $wdrl->senderWallet->user : null;
                    return isset($user) ? $user->first_name . ' ' . $user->last_name : 'N/A';
                })
                ->addColumn('receiver', function ($wdrl) {
                    if (!empty($wdrl->receiverWallet) && $wdrl->receiverWallet->type == CO_WALLET) return  'Multi-signature Pocket: '.$wdrl->receiverWallet->name;
                    else
                    return isset($wdrl->receiverWallet->user) ? $wdrl->receiverWallet->user->first_name . ' ' . $wdrl->receiverWallet->user->last_name : 'N/A';
                })
                ->addColumn('status', function ($wdrl) {
                    return deposit_status($wdrl->status);
                })
                ->make(true);
        }

        return view('admin.transaction.all-transaction');
    }



    // pending withdrawal list
    public function adminPendingWithdrawal(Request $request)
    {
        $data['title'] = __('Pending Withdrawal');
        if ($request->ajax()) {
            $withdrawal = WithdrawHistory::select(
                'withdraw_histories.id',
                'withdraw_histories.address'
                , 'withdraw_histories.amount'
                , 'withdraw_histories.user_id'
                , 'withdraw_histories.fees'
                , 'withdraw_histories.transaction_hash'
                , 'withdraw_histories.confirmations'
                , 'withdraw_histories.address_type as addr_type'
                , 'withdraw_histories.updated_at'
                , 'withdraw_histories.wallet_id'
                , 'withdraw_histories.coin_type'
                , 'withdraw_histories.receiver_wallet_id'
            )->where(['withdraw_histories.status' => STATUS_PENDING])
                ->orderBy('withdraw_histories.id', 'desc');

            return datatables()->of($withdrawal)
                ->addColumn('address_type', function ($wdrl) {
                    return addressType($wdrl->addr_type);
                })
                ->addColumn('coin_type', function ($wdrl) {
                    return find_coin_type($wdrl->coin_type);
                })
                ->addColumn('sender', function ($wdrl) {
                    if(!empty($wdrl->user)) $user = $wdrl->user;
                    else $user = isset($wdrl->senderWallet) ? $wdrl->senderWallet->user : null;
                    return isset($user) ? $user->first_name . ' ' . $user->last_name : 'N/A';
                })
                ->addColumn('receiver', function ($wdrl) {
                    if (!empty($wdrl->receiverWallet) && $wdrl->receiverWallet->type == CO_WALLET) return  'Multi-signature Pocket: '.$wdrl->receiverWallet->name;
                    else
                    return isset($wdrl->receiverWallet->user) ? $wdrl->receiverWallet->user->first_name . ' ' . $wdrl->receiverWallet->user->last_name : 'N/A';
                })
                ->addColumn('actions', function ($wdrl) {
                    $action = '<div class="activity-icon"><ul>';
                    $action .= accept_html('adminAcceptPendingWithdrawal',encrypt($wdrl->id));
                    $action .= reject_html('adminRejectPendingWithdrawal',encrypt($wdrl->id));
                    $action .= '</ul> </div>';

                    return $action;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('admin.transaction.pending-withdrawal', $data);
    }

    // rejected withdrawal list
    public function adminRejectedWithdrawal(Request $request)
    {
        $data['title'] = __('Rejected Withdrawal');
        if ($request->ajax()) {
            $withdrawal = WithdrawHistory::select(
                'withdraw_histories.address'
                , 'withdraw_histories.amount'
                , 'withdraw_histories.user_id'
                , 'withdraw_histories.fees'
                , 'withdraw_histories.transaction_hash'
                , 'withdraw_histories.confirmations'
                , 'withdraw_histories.address_type as addr_type'
                , 'withdraw_histories.updated_at'
                , 'withdraw_histories.wallet_id'
                , 'withdraw_histories.coin_type'
                , 'withdraw_histories.receiver_wallet_id'
            )->where(['withdraw_histories.status' => STATUS_REJECTED])
                ->orderBy('withdraw_histories.id', 'desc');

            return datatables()->of($withdrawal)
                ->addColumn('address_type', function ($wdrl) {
                    return addressType($wdrl->addr_type);
                })
                ->addColumn('coin_type', function ($wdrl) {
                    return find_coin_type($wdrl->coin_type);
                })
                ->addColumn('sender', function ($wdrl) {
                    if(!empty($wdrl->user)) $user = $wdrl->user;
                    else $user = isset($wdrl->senderWallet) ? $wdrl->senderWallet->user : null;
                    return isset($user) ? $user->first_name . ' ' . $user->last_name : 'N/A';
                })
                ->addColumn('receiver', function ($wdrl) {
                    if (!empty($wdrl->receiverWallet) && $wdrl->receiverWallet->type == CO_WALLET) return  'Multi-signature Pocket: '.$wdrl->receiverWallet->name;
                    else
                    return isset($wdrl->receiverWallet->user) ? $wdrl->receiverWallet->user->first_name . ' ' . $wdrl->receiverWallet->user->last_name : 'N/A';
                })
                ->make(true);
        }

        return view('admin.transaction.pending-withdrawal', $data);
    }

    // active withdrawal list
    public function adminActiveWithdrawal(Request $request)
    {
        $data['title'] = __('Completed Withdrawal');
        if ($request->ajax()) {
            $withdrawal = WithdrawHistory::select(
                'withdraw_histories.address'
                , 'withdraw_histories.amount'
                , 'withdraw_histories.user_id'
                , 'withdraw_histories.fees'
                , 'withdraw_histories.transaction_hash'
                , 'withdraw_histories.confirmations'
                , 'withdraw_histories.address_type as addr_type'
                , 'withdraw_histories.updated_at'
                , 'withdraw_histories.wallet_id'
                , 'withdraw_histories.coin_type'
                , 'withdraw_histories.receiver_wallet_id'
            )->where(['withdraw_histories.status' => STATUS_SUCCESS])
                ->orderBy('withdraw_histories.id', 'desc');

            return datatables()->of($withdrawal)
                ->addColumn('address_type', function ($wdrl) {
                    return addressType($wdrl->addr_type);
                })
                ->addColumn('coin_type', function ($wdrl) {
                    return find_coin_type($wdrl->coin_type);
                })
                ->addColumn('sender', function ($wdrl) {
                    if(!empty($wdrl->user)) $user = $wdrl->user;
                    else $user = isset($wdrl->senderWallet) ? $wdrl->senderWallet->user : null;
                    return isset($user) ? $user->first_name . ' ' . $user->last_name : 'N/A';
                })
                ->addColumn('receiver', function ($wdrl) {
                    if (!empty($wdrl->receiverWallet) && $wdrl->receiverWallet->type == CO_WALLET) return  'Multi-signature Pocket: '.$wdrl->receiverWallet->name;
                    else
                    return isset($wdrl->receiverWallet->user) ? $wdrl->receiverWallet->user->first_name . ' ' . $wdrl->receiverWallet->user->last_name : 'N/A';
                })
                ->make(true);
        }

        return view('admin.transaction.pending-withdrawal', $data);
    }

    // accept process of pending withdrawal
    public function adminAcceptPendingWithdrawal($id)
    {
        if (isset($id)) {
            try {
                $wdrl_id = decrypt($id);
            } catch (\Exception $e) {
                return redirect()->back();
            }
            $transaction = WithdrawHistory::with('wallet')->with('users')->where(['id' => $wdrl_id, 'status' => STATUS_PENDING])->firstOrFail();
            if (!empty($transaction)) {
                if ($transaction->address_type == ADDRESS_TYPE_INTERNAL) {

                    $deposit = DepositeTransaction::where(['transaction_id' =>$transaction->transaction_hash, 'address' => $transaction->address])->update(['status' => STATUS_SUCCESS]);

                    Wallet::where(['id' => $transaction->receiver_wallet_id])->increment('balance', $transaction->amount);
                    $transaction->status = STATUS_SUCCESS;
                    $transaction->save();

                    return redirect()->back()->with('success', __('Pending withdrawal accepted successfully.'));

                } elseif ($transaction->address_type == ADDRESS_TYPE_EXTERNAL) {
                    try {
                        $result =  $this->transService->acceptPendingExternalWithdrawal($transaction);
                        if ($result['success'] == true) {
                            return redirect()->back()->with('success', $result['message']);
                        } else {
                            return redirect()->back()->with('dismiss', $result['message']);
                        }
                    } catch(\Exception $e) {
                        $this->logger->log('adminAcceptPendingWithdrawal', $e->getMessage());
                        return redirect()->back()->with('dismiss', __('Something went wrong'));
                    }
                }
            }

            return redirect()->back()->with('dismiss', __('Something went wrong! Please try again!'));
        }
    }

    // pending withdrawal reject process
    public function adminRejectPendingWithdrawal($id)
    {
        try {
            if (isset($id)) {
                try {
                    $wdrl_id = decrypt($id);
                } catch (\Exception $e) {
                    return redirect()->back();
                }
                $data['message'] = __('Something went wrong');
                $transaction = WithdrawHistory::where(['id' => $wdrl_id, 'status' => STATUS_PENDING])->firstOrFail();

                if (!empty($transaction)) {
                    if ($transaction->address_type == ADDRESS_TYPE_INTERNAL) {

                        Wallet::where(['id' => $transaction->wallet_id])->increment('balance', $transaction->amount);
                        $transaction->status = STATUS_REJECTED;
                        $transaction->update();

                        $deposit = DepositeTransaction::where(['transaction_id' =>$transaction->transaction_hash, 'address' => $transaction->address])->update(['status' => STATUS_REJECTED]);

                        $data['message'] = __('Pending withdrawal rejected Successfully.');
                    } elseif ($transaction->address_type == ADDRESS_TYPE_EXTERNAL) {
                        Wallet::where(['id' => $transaction->wallet_id])->increment('balance', $transaction->amount);
                        $transaction->status = STATUS_REJECTED;

                        $transaction->update();

                        $data['message'] = __('Pending withdrawal rejected Successfully.');
                    }

                    return redirect()->back()->with('success', $data['message']);
                } else {
                    return redirect()->back()->with('dismiss', __('Transaction not found'));
                }
            }
            return redirect()->back()->with('dismiss', __('Something went wrong! Please try again!'));
        } catch (\Exception $e) {
            $this->logger->log('adminRejectPendingWithdrawal', $e->getMessage());
            return redirect()->back()->with('dismiss', __('Something went wrong! Please try again!'));
        }
    }

    // admin accept pending withdrawal
    public function adminPendingWithdrawalAcceptProcess(Request $request)
    {
        if (isset($request->withdrawal_id)) {
            if (empty($request->transaction_hash)) {
                return redirect()->back()->with('dismiss', __('Transaction hash is required'));
            }
            try {
                $wdrl_id = decrypt($request->withdrawal_id);
            } catch (\Exception $e) {
                return redirect()->back();
            }
            $transaction = WithdrawHistory::with('wallet')->with('users')->where(['id' => $wdrl_id, 'status' => STATUS_PENDING])->firstOrFail();
            if (!empty($transaction)) {
                try {
                    $transaction->transaction_hash = $request->transaction_hash;
                    $transaction->status = STATUS_SUCCESS;
                    $transaction->update();

                    return redirect()->back()->with('success', __('Pending withdrawal accepted Successfully.'));

                } catch(\Exception $e) {
                    return redirect()->back()->with('dismiss', $e->getMessage());
                }
            } else {
                return redirect()->back()->with('dismiss', __('Transaction not found'));
            }
        } else {
            return redirect()->back()->with('dismiss', __('Withdrawal id not found'));
        }
    }
}
