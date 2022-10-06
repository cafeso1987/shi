<?php
/**
 * Created by PhpStorm.
 * User: bacchu
 * Date: 1/25/22
 * Time: 5:19 PM
 */

namespace App\Http\Repositories;

use App\Http\Services\ERC20TokenApi;
use App\Model\AdminReceiveTokenTransactionHistory;
use App\Model\Coin;
use App\Model\DepositeTransaction;
use App\Model\EstimateGasFeesTransactionHistory;
use App\Model\WalletAddressHistory;


class CustomTokenRepository
{

    public function depositCustomToken()
    {
        try {
            $bep20Token = Coin::join('coin_settings', 'coin_settings.coin_id', '=', 'coins.id')
                ->where(['network' => BEP20_TOKEN])
                ->first();
            if (!empty($bep20Token)) {
                return $this->bep20TokenDeposit($bep20Token);
            }
        } catch (\Exception $e) {
            storeException('depositCustomToken', $e->getMessage());
        }
    }
    public function depositCustomERC20Token()
    {
        try {
            $erc20Token = Coin::join('coin_settings', 'coin_settings.coin_id', '=', 'coins.id')
                ->where(['coins.network' => ERC20_TOKEN])
                ->first();
            if (!empty($erc20Token)) {
                return $this->ecr20TokenDeposit($erc20Token);
            }
        } catch (\Exception $e) {
            storeException('depositCustomToken', $e->getMessage());
        }
    }

    public function ecr20TokenDeposit($coin)
    {
        try {
            $latestTransactions = $this->getLatestTransactionFromBlock($coin);
            storeException('$latestTransactions',$latestTransactions);
            if ($latestTransactions['success'] == true) {
                foreach($latestTransactions['data'] as $transaction) {
                    $checkDeposit = $this->checkAddressAndDeposit($transaction->to_address,$transaction->tx_hash,$transaction->amount,$transaction->from_address);
                    if ($checkDeposit['success'] == true) {
                        $deposit = $checkDeposit['data'];
                        $userPk = get_wallet_personal_add($transaction->to_address,$checkDeposit['pk']);
                        $checkGasFees = $this->checkEstimateGasFees($coin,$transaction->to_address,$transaction->amount);
                        if($checkGasFees['success'] == true) {

                            $estimateFees = $checkGasFees['data']->estimateGasFees;
                            $gas = bcadd($estimateFees , (bcdiv(bcmul($estimateFees, 30,8),100,8)),8);
                            storeException('Gas',$gas);

                            $checkAddressBalance = $this->checkWalletAddressBalance($coin,$transaction->to_address);
                            if ($checkAddressBalance['success'] == true) {
                                $walletNetBalance = $checkAddressBalance['data']->net_balance;
                                storeException('$walletNetBalance',$walletNetBalance);
                                if ($walletNetBalance >= $gas) {
                                    $estimateGas = 0;
                                    storeException('$estimateGas 0 ',$estimateGas);
                                } else {
                                    $estimateGas = bcsub($gas, $walletNetBalance,8);
                                    storeException('$estimateGas have ',$estimateGas);
                                }
                                if ($estimateGas > 0) {
                                    storeException('sendFeesToUserAddress ',$estimateGas);
                                    $sendFees = $this->sendFeesToUserAddress($coin,$transaction->to_address,$estimateGas,$deposit->receiver_wallet_id,$deposit->id);
                                    if ($sendFees['success'] == true) {

                                        storeException('depositCustomToken -> ', 'sendFeesToUserAddress success . the next process will held on getDepositBalanceFromUserJob');
                                    } else {
                                        storeException('depositCustomToken', 'send fees process failed');
                                    }
                                } else {
                                    storeException('sendFeesToUserAddress ', 'no gas needed');
                                    $checkAddressBalanceAgain2 = $this->checkWalletAddressBalance($coin,$transaction->to_address);
                                    storeException('depositCustomToken  $checkAddressBalanceAgain2',$checkAddressBalanceAgain2);
                                    if ($checkAddressBalanceAgain2['success'] == true) {
                                        $receiveToken = $this->receiveTokenFromUserAddress($coin,$transaction->to_address, $transaction->amount, $userPk, $deposit->id);
                                        if ($receiveToken['success'] == true) {
                                            $this->updateUserWallet($deposit, $receiveToken['data']->hash);
                                        } else {
                                            storeException('depositCustomToken', 'token received process failed');
                                        }
                                    } else {
                                        storeException('depositCustomToken', 'again 2 get balance failed');
                                    }
                                }
                            } else {
                                storeException('depositCustomToken', 'get balance failed');
                            }
                        } else {
                            storeException('depositCustomToken', 'check gas fees calculate failed');
                        }
                    }
                }
            } else {
                storeException('depositCustomToken', $latestTransactions['message']);
            }
            return $latestTransactions;
        } catch (\Exception $e) {
            storeException('bep20TokenDeposit', $e->getMessage());
        }
    }

    public function bep20TokenDeposit($coin)
    {
        try {
            $latestTransactions = $this->getLatestTransactionFromBlock($coin);
            if ($latestTransactions['success'] == true) {
                foreach($latestTransactions['data'] as $transaction) {
                    $checkDeposit = $this->checkAddressAndDeposit($transaction->to_address,$transaction->tx_hash,$transaction->amount,$transaction->from_address);
                    if ($checkDeposit['success'] == true) {
                        $deposit = $checkDeposit['data'];
                        $userPk = get_wallet_personal_add($transaction->to_address,$checkDeposit['pk']);
                        $checkGasFees = $this->checkEstimateGasFees($coin,$transaction->to_address,$transaction->amount);
                        if($checkGasFees['success'] == true) {

                            $estimateFees = $checkGasFees['data']->estimateGasFees;
                            $gas = bcadd($estimateFees , (bcdiv(bcmul($estimateFees, 30,8),100,8)),8);
                            storeException('Gas',$gas);

                            $checkAddressBalance = $this->checkWalletAddressBalance($coin,$transaction->to_address);
                            if ($checkAddressBalance['success'] == true) {
                                $walletNetBalance = $checkAddressBalance['data']->net_balance;
                                storeException('$walletNetBalance',$walletNetBalance);
                                if ($walletNetBalance >= $gas) {
                                    $estimateGas = 0;
                                    storeException('$estimateGas 0 ',$estimateGas);
                                } else {
                                    $estimateGas = bcsub($gas, $walletNetBalance,8);
                                    storeException('$estimateGas have ',$estimateGas);
                                }
                                if ($estimateGas > 0) {
                                    storeException('sendFeesToUserAddress ',$estimateGas);
                                    $sendFees = $this->sendFeesToUserAddress($coin,$transaction->to_address,$estimateGas,$deposit->receiver_wallet_id,$deposit->id);
                                    if ($sendFees['success'] == true) {

                                        storeException('depositCustomToken -> ', 'sendFeesToUserAddress success . the next process will held on getDepositBalanceFromUserJob');
                                    } else {
                                        storeException('depositCustomToken', 'send fees process failed');
                                    }
                                } else {
                                    storeException('sendFeesToUserAddress ', 'no gas needed');
                                    $checkAddressBalanceAgain2 = $this->checkWalletAddressBalance($coin,$transaction->to_address);
                                    storeException('depositCustomToken  $checkAddressBalanceAgain2',$checkAddressBalanceAgain2);
                                    if ($checkAddressBalanceAgain2['success'] == true) {
                                        $receiveToken = $this->receiveTokenFromUserAddress($coin,$transaction->to_address, $transaction->amount, $userPk, $deposit->id);
                                        if ($receiveToken['success'] == true) {
                                            $this->updateUserWallet($deposit, $receiveToken['data']->hash);
                                        } else {
                                            storeException('depositCustomToken', 'token received process failed');
                                        }
                                    } else {
                                        storeException('depositCustomToken', 'again 2 get balance failed');
                                    }
                                }
                            } else {
                                storeException('depositCustomToken', 'get balance failed');
                            }
                        } else {
                            storeException('depositCustomToken', 'check gas fees calculate failed');
                        }
                    }
                }
            } else {
                storeException('depositCustomToken', $latestTransactions['message']);
            }
            return $latestTransactions;
        } catch (\Exception $e) {
            storeException('bep20TokenDeposit', $e->getMessage());
        }
    }
    // update wallet
    public function updateUserWallet($deposit,$hash)
    {
        try {
            DepositeTransaction::where(['id' => $deposit->id])
                ->update([
                    'status' => STATUS_SUCCESS,
//                    'transaction_id' => $hash
                ]);
            $userWallet = $deposit->receiverWallet;
            storeException('depositCustomToken', 'before update wallet balance => '. $userWallet->balance);
            $userWallet->increment('balance',$deposit->amount);
            storeException('depositCustomToken', 'after update wallet balance => '. $userWallet->balance);
            storeException('depositCustomToken', 'update one wallet id => '. $deposit->receiver_wallet_id);
            storeException('depositCustomToken', 'Deposit process success');
        } catch (\Exception $e) {
            storeException('updateUserWallet', $e->getMessage());
        }
    }

    // check address and deposit
    public function checkAddressAndDeposit($address,$hash,$amount,$fromAddress)
    {
        try {
            $checkAddress = WalletAddressHistory::where(['address' => $address])->first();
            if(!empty($checkAddress)) {
                $checkDeposit = DepositeTransaction::where(['address' => $address, 'transaction_id' => $hash])->first();
                if ($checkDeposit) {
                    storeException('checkAddressAndDeposit', 'deposit already in db '.$hash);
                    $response = ['success' => false, 'message' => __('This hash already in db'), 'data' => []];
                } else {
                    $createDeposit = DepositeTransaction::create([
                        'address' => $address,
                        'from_address' => $fromAddress,
                        'receiver_wallet_id' => $checkAddress->wallet_id,
                        'address_type' => ADDRESS_TYPE_EXTERNAL,
                        'coin_type' => $checkAddress->coin_type,
                        'amount' => $amount,
                        'transaction_id' => $hash,
                    ]);
                    storeException('deposit', $createDeposit);
                    $response = ['success' => true, 'message' => __('New deposit'), 'data' => $createDeposit,'pk' => $checkAddress->wallet_key];
                }
            } else {
                $response = ['success' => false, 'message' => __('This address not found in db'), 'data' => []];
            }
        } catch (\Exception $e) {
            storeException('checkAddressAndDeposit', $e->getMessage());
            $response = ['success' => false, 'message' => $e->getMessage(), 'data' => []];
        }
        return $response;
    }

    // get latest transaction block data
    public function getLatestTransactionFromBlock($coin)
    {
        $response = ['success' => false, 'message' => 'failed', 'data' => []];
        try {
            $tokenApi = new ERC20TokenApi($coin);
            $result = $tokenApi->getContractTransferEvent();
            if ($result['success'] == true) {
                $response = ['success' => $result['success'], 'message' => $result['message'], 'data' => $result['data']->result];
            } else {
                $response = ['success' => false, 'message' => __('No transaction found'), 'data' => []];
            }

        } catch (\Exception $e) {
            storeException('getLatestTransactionFromBlock', $e->getMessage());
            $response = ['success' => false, 'message' => $e->getMessage(), 'data' => []];
        }
        return $response;
    }

    // check estimate gas for sending token
    public function checkEstimateGasFees($coin,$address,$amount)
    {
        $response = ['success' => false, 'message' => 'failed', 'data' => []];
        try {
            $requestData = [
                "amount_value" => $amount,
                "from_address" => $address,
                "to_address" => $coin->wallet_address
            ];
            storeException('checkEstimateGasFees', json_encode($requestData));
            $tokenApi = new ERC20TokenApi($coin);
            $check = $tokenApi->checkEstimateGas($requestData);
            storeException('checkEstimateGasFees', $check);
            if ($check['success'] == true) {
                $response = ['success' => true, 'message' => $check['message'], 'data' => $check['data']];
            } else {
                $response = ['success' => false, 'message' => $check['message'], 'data' => []];
            }
        } catch (\Exception $e) {
            storeException('checkEstimateGasFees', $e->getMessage());
            $response = ['success' => false, 'message' => $e->getMessage(), 'data' => []];
        }

        return $response;
    }

    // send estimate gas fees to address
    public function sendFeesToUserAddress($coin,$address,$amount,$wallet_id,$depositId)
    {
        try {
            $requestData = [
                "amount_value" => $amount,
                "from_address" => $coin->wallet_address,
                "to_address" => $address,
                "contracts" => $coin->wallet_key
            ];
            $tokenApi = new ERC20TokenApi($coin);
            $result = $tokenApi->sendEth($requestData);
            storeException('sendFeesToUserAddress result ', $result);
            if ($result['success'] == true) {
                $this->saveEstimateGasFeesTransaction($wallet_id,$result['data']->hash,$amount,$coin->wallet_address,$address,$depositId,$coin->contract_coin_name);
                $response = ['success' => true, 'message' => __('Fess send successfully'), 'data' => []];
            } else {
                $response = ['success' => false, 'message' => $result['message'], 'data' => []];
            }
        } catch (\Exception $e) {
            storeException('sendFeesToUserAddress', $e->getMessage());
            $response = ['success' => false, 'message' => $e->getMessage(), 'data' => []];
        }
        return $response;
    }

    // save estimate gas fees transaction
    public function saveEstimateGasFeesTransaction($wallet_id,$hash,$amount,$adminAddress,$userAddress,$depositId,$contractCoinName)
    {
        try {
            $data = EstimateGasFeesTransactionHistory::create([
                'unique_code' => uniqid().date('').time(),
                'wallet_id' => $wallet_id,
                'deposit_id' => $depositId,
                'amount' => $amount,
                'coin_type' => $contractCoinName,
                'admin_address' => $adminAddress,
                'user_address' => $userAddress,
                'transaction_hash' => $hash,
                'status' => STATUS_PENDING
            ]);
            storeException('saveEstimateGasFeesTransaction', json_encode($data));
        } catch (\Exception $e) {
            storeException('saveEstimateGasFeesTransaction', $e->getMessage());
        }
    }

    // receive token from user address
    public function receiveTokenFromUserAddress($coin,$address,$amount,$userPk,$depositId)
    {
        try {
            $requestData = [
                "amount_value" => $amount,
                "from_address" => $address,
                "to_address" => $coin->wallet_address,
                "contracts" => $userPk
            ];

            $checkAddressBalanceAgain = $this->checkWalletAddressAllBalance($coin,$address);
            storeException('receiveTokenFromUserAddress  $check Address All Balance ',$checkAddressBalanceAgain);
            $tokenApi = new ERC20TokenApi($coin);
            $result = $tokenApi->sendCustomToken($requestData);
            storeException('receiveTokenFromUserAddress $result', $result);
            if ($result['success'] == true) {
                $this->saveReceiveTransaction($result['data']->used_gas,$result['data']->hash,$amount,$coin->wallet_address,$address,$depositId);
                $response = ['success' => true, 'message' => __('Token received successfully'), 'data' => $result['data']];
            } else {
                $response = ['success' => false, 'message' => $result['message'], 'data' => []];
            }
        } catch (\Exception $e) {
            storeException('receiveTokenFromUserAddress', $e->getMessage());
            $response = ['success' => false, 'message' => $e->getMessage(), 'data' => []];
        }
        return $response;
    }

    // save receive token transaction
    public function saveReceiveTransaction($fees,$hash,$amount,$adminAddress,$userAddress,$depositId)
    {
        try {
            $data = AdminReceiveTokenTransactionHistory::create([
                'unique_code' => uniqid().date('').time(),
                'amount' => $amount,
                'deposit_id' => $depositId,
                'fees' => $fees,
                'to_address' => $adminAddress,
                'from_address' => $userAddress,
                'transaction_hash' => $hash,
                'status' => STATUS_SUCCESS
            ]);
            storeException('saveReceiveTransaction', json_encode($data));
        } catch (\Exception $e) {
            storeException('saveReceiveTransaction', $e->getMessage());
        }
    }

    // check wallet balance
    public function checkWalletAddressBalance($coin,$address)
    {
        try {
            $requestData = array(
                "type" => 1,
                "address" => $address,
            );
            $tokenApi = new ERC20TokenApi($coin);
            $result = $tokenApi->checkWalletBalance($requestData);
            if ($result['success'] == true) {
                $response = ['success' => true, 'message' => __('Get balance'), 'data' => $result['data'] ];
            } else {
                storeException('sendFeesToUserAddress', $result['message']);
                $response = ['success' => false, 'message' => $result['message'], 'data' => []];
            }
        } catch (\Exception $e) {
            storeException('checkWalletAddressBalance', $e->getMessage());
            $response = ['success' => false, 'message' => $e->getMessage(), 'data' => []];
        }
        return $response;
    }

    // check wallet balance
    public function checkWalletAddressAllBalance($coin,$address)
    {
        try {
            $requestData = array(
                "type" => 3,
                "address" => $address,
            );
            $tokenApi = new ERC20TokenApi($coin);
            $result = $tokenApi->checkWalletBalance($requestData);
            if ($result['success'] == true) {
                $response = ['success' => true, 'message' => __('Get balance'), 'data' => $result['data'] ];
            } else {
                storeException('sendFeesToUserAddress', $result['message']);
                $response = ['success' => false, 'message' => $result['message'], 'data' => []];
            }
        } catch (\Exception $e) {
            storeException('checkWalletAddressBalance', $e->getMessage());
            $response = ['success' => false, 'message' => $e->getMessage(), 'data' => []];
        }
        return $response;
    }

    // token receive manually by admin
    public function tokenReceiveManuallyByAdmin($transaction,$adminId)
    {
        try {
            if ($transaction->status == STATUS_PENDING) {
                $this->tokenReceiveManuallyByAdminProcess($transaction,$adminId);
            }
        } catch (\Exception $e) {
            storeException('tokenReceiveManuallyByAdmin', $e->getMessage());
        }
    }

    // token Receive Manually By Admin process
    public function tokenReceiveManuallyByAdminProcess($transaction,$adminId)
    {
        try {
            if ($transaction->status == STATUS_PENDING) {
                $coin = Coin::join('coin_settings', 'coin_settings.coin_id', '=', 'coins.id')
                    ->where(['coins.coin_type' => $transaction->coin_type])
                    ->first();
                $sendAmount = (float)$transaction->amount;
                $checkAddress = $this->checkAddress($transaction->address);
                $userPk = get_wallet_personal_add($transaction->address,$checkAddress->wallet_key);
                $checkGasFees = $this->checkEstimateGasFees($coin,$transaction->address,$sendAmount);
                if($checkGasFees['success'] == true) {
                    storeException('Estimate gas ',$checkGasFees['data']->estimateGasFees);
                    $estimateFees = $checkGasFees['data']->estimateGasFees;
                    $gas = bcadd($estimateFees , (bcdiv(bcmul($estimateFees, 30,8),100,8)),8);
                    storeException('Gas',$gas);

                    $checkAddressBalance = $this->checkWalletAddressBalance($coin,$transaction->address);
                    if ($checkAddressBalance['success'] == true) {
                        $walletNetBalance = $checkAddressBalance['data']->net_balance;
                        storeException('$walletNetBalance',$walletNetBalance);
                        if ($walletNetBalance >= $gas) {
                            $estimateGas = 0;
                            storeException('$estimateGas 0 ',$estimateGas);
                        } else {
                            $estimateGas = bcsub($gas, $walletNetBalance,8);
                            storeException('$estimateGas have ',$estimateGas);
                        }
                        if ($estimateGas > 0) {
                            storeException('sendFeesToUserAddress ',$estimateGas);
                            $sendFees = $this->sendFeesToUserAddress($coin,$transaction->address,$estimateGas,$checkAddress->wallet_id,$transaction->id);
                            if ($sendFees['success'] == true) {
                                storeException('tokenReceiveManuallyByAdminProcess -> ', 'sendFeesToUserAddress success . the next process will held on getDepositBalanceFromUserJob');

                            } else {
                                storeException('tokenReceiveManuallyByAdminProcess', 'send fees process failed');
                            }
                        } else {
                            storeException('sendFeesToUserAddress ', 'no gas needed');
                            $checkAddressBalanceAgain2 = $this->checkWalletAddressBalance($coin,$transaction->address);
                            storeException('tokenReceiveManuallyByAdminProcess  $checkAddressBalanceAgain2',$checkAddressBalanceAgain2);
                            if ($checkAddressBalanceAgain2['success'] == true) {
                                $receiveToken = $this->receiveTokenFromUserAddressByAdminPanel($coin,$transaction->address, $sendAmount, $userPk, $transaction->id);
                                if ($receiveToken['success'] == true) {
                                    $this->updateUserWalletByAdmin($transaction, $adminId);
                                } else {
                                    storeException('tokenReceiveManuallyByAdminProcess', 'token received process failed');
                                }
                            } else {
                                storeException('tokenReceiveManuallyByAdminProcess', 'again 2 get balance failed');
                            }
                        }
                    } else {
                        storeException('tokenReceiveManuallyByAdminProcess', 'get balance failed');
                    }
                } else {
                    storeException('tokenReceiveManuallyByAdminProcess', 'check gas fees calculate failed');
                }
            } else {
                storeException('tokenReceiveManuallyByAdminProcess', 'transaction is not pending');
            }
        } catch (\Exception $e) {
            storeException('tokenReceiveManuallyByAdminProcess', $e->getMessage());
        }
    }

    // check address
    public function checkAddress($address)
    {
        return WalletAddressHistory::where(['address' => $address])->first();
    }

    // receive token from user address by admin
    public function receiveTokenFromUserAddressByAdminPanel($coin,$address,$amount,$userPk,$depositId)
    {
        try {
            $requestData = [
                "amount_value" => $amount,
                "from_address" => $address,
                "to_address" => $coin->wallet_address,
                "contracts" => $userPk
            ];

            $checkAddressBalanceAgain = $this->checkWalletAddressAllBalance($coin,$address);
            storeException('receiveTokenFromUserAddressByAdminPanel  $check Address All Balance ',$checkAddressBalanceAgain);
            $tokenApi = new ERC20TokenApi($coin);
            $result = $tokenApi->sendCustomToken($requestData);
            storeException('receiveTokenFromUserAddressByAdminPanel $result', $result);
            if ($result['success'] == true) {
                $this->saveReceiveTransaction($result['data']->used_gas,$result['data']->hash,$amount,$coin->wallet_address,$address,$depositId);
                $response = ['success' => true, 'message' => __('Token received successfully'), 'data' => $result['data']];
            } else {
                storeException('receiveTokenFromUserAddressByAdminPanel', $result['message']);
                $response = ['success' => false, 'message' => $result['message'], 'data' => []];
            }
        } catch (\Exception $e) {
            storeException('receiveTokenFromUserAddressByAdminPanel', $e->getMessage());
            $response = ['success' => false, 'message' => $e->getMessage(), 'data' => []];
        }
        return $response;
    }

    // update wallet
    public function updateUserWalletByAdmin($deposit,$adminId)
    {
        try {
            DepositeTransaction::where(['id' => $deposit->id])
                ->update([
                    'status' => STATUS_SUCCESS,
                    'updated_by' => $adminId
                ]);
            $userWallet = $deposit->receiverWallet;
            storeException('updateUserWalletByAdmin', 'before update wallet balance => '. $userWallet->balance);
            $userWallet->increment('balance',$deposit->amount);
            storeException('updateUserWalletByAdmin', 'after update wallet balance => '. $userWallet->balance);
            storeException('updateUserWalletByAdmin', 'update one wallet id => '. $deposit->receiver_wallet_id);
            storeException('updateUserWalletByAdmin', 'Deposit process success');
        } catch (\Exception $e) {
            storeException('updateUserWalletByAdmin', $e->getMessage());
        }
    }


    // get deposit token balance from user
    public function getDepositTokenFromUser()
    {
        try {
            $checkFeesHistories = EstimateGasFeesTransactionHistory::where(['status' => STATUS_PENDING])->get();
            if (isset($checkFeesHistories[0])) {
                foreach ($checkFeesHistories as $history) {
                    $transaction = DepositeTransaction::where(['id' => $history->deposit_id])->first();
                    if (isset($transaction)) {
                        if ($transaction->status == STATUS_PENDING) {
                            $coin = Coin::join('coin_settings', 'coin_settings.coin_id', '=', 'coins.id')
                                ->where(['coins.coin_type' => $transaction->coin_type])
                                ->first();
                            $sendAmount = (float)$transaction->amount;
                            $checkAddress = $this->checkAddress($transaction->address);
                            $userPk = get_wallet_personal_add($transaction->address,$checkAddress->wallet_key);
                            $checkAddressBalanceAgain = $this->checkWalletAddressBalance($coin,$transaction->address);
                            storeException('getDepositTokenFromUser  $checkAddressBalanceAgain -> ',$checkAddressBalanceAgain);
                            if ($checkAddressBalanceAgain['success'] == true) {
                                $receiveToken = $this->receiveTokenFromUserAddress($coin,$transaction->address, $sendAmount, $userPk, $transaction->id);
                                if ($receiveToken['success'] == true) {
                                    $this->updateUserWallet($transaction, $receiveToken['data']->hash);
                                    $history->update(['status' => STATUS_ACTIVE]);
                                    storeException('getDepositTokenFromUser', 'status updated balance updated deposit updated deposit id => '.$transaction->id);
                                } else {
                                    storeException('getDepositTokenFromUser', 'token received process failed');
                                }
                            } else {
                                storeException('getDepositTokenFromUser', 'again 2 get balance failed');
                            }
                        } else {
                            $history->update(['status' => STATUS_ACTIVE]);
                            storeException('getDepositTokenFromUser', 'status updated because already deposited deposit id => '.$transaction->id);
                        }
                    } else {

                    }
                }
            } else {

            }
        } catch (\Exception $e) {
            storeException('getDepositTokenFromUser', $e->getMessage());
        }
    }

}
