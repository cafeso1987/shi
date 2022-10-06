<?php

namespace App\Http\Services;


use App\Http\Repositories\BuyOrderRepository;
use App\Http\Repositories\CoinPairRepository;
use App\Http\Repositories\SellOrderRepository;
use App\Http\Repositories\StopLimitRepository;
use App\Http\Repositories\TransactionRepository;
use App\Http\Repositories\UserWalletRepository;
use App\Http\Services\BaseService;
use App\Jobs\BroadcastOrderBookBookmarkJob;
use App\Jobs\ConditionBuyOrderProcessJob;
use App\Jobs\ConditionSellOrdersProcessJob;
use App\Jobs\StopLimitProcessJob;
use App\Jobs\TradingViewChartJob;
use App\Jobs\TransactionBroadcastJob;
use App\Jobs\DistributeAffiliationBonus;
use App\Model\Buy;
use App\Model\Coin;
use App\Model\CoinPair;
use App\Model\FavouriteOrderBook;
use App\Model\Notification;
use App\Model\Sell;
use App\Model\StopLimit;
use App\Model\Transaction;
use App\Model\UserWallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Services\Logger;

class BuySellTransactionService extends BaseService
{
    public $model = Transaction::class;
    public $repository = TransactionRepository::class;
    public $logger = null;

    private $amountToBeProcessed = 0;
    private $buyAbleAmount = 0;
    private $sellAbleAmount = 0;
    private $bot = null;
    public function __construct()
    {
        parent::__construct($this->model, $this->repository);
        $this->logger = new Logger(env('TRADING_LOG'));

    }

    public function getOrders()
    {
        return $this->object->getOrders();
    }

    public function process($orderId, $orderType)
    {
//        Log::info(json_encode($this->logger));
        try {
            $beingProcessType = '';
            if ($orderType == 'buy') {
                $beingProcessType = 'sell';
                $repo = new BuyOrderRepository(Buy::class);
                $order = $repo->getDocs(['id' => $orderId, 'status' => 0])->first();
            } else {
                $beingProcessType = 'buy';
                $repo = new SellOrderRepository(Sell::class);
                $order = $repo->getDocs(['id' => $orderId, 'status' => 0])->first();
            }
            if (empty($order)) {
                $message = __("Order Type: :type Order Id: :orderId Order not found in the database.", ['type' => $orderType, 'orderId' => $orderId]);
                $this->logger->log('Order', $message);
                return $message;
            }
            $baseCoin = $order->baseCoin;
            $tradeCoin = $order->tradeCoin;

            $this->logger->log('[', "", false);
            $this->logger->log('Order', "Order Type: $orderType Order Amount: $order->amount $tradeCoin->coin_type Order Price: $order->price $baseCoin->coin_type");


            $beingProcessingOrders = $this->_getBeingProcessingOrders($order, $orderType);
            if ($beingProcessingOrders->isEmpty()) {
//            $this->closeOrder($order, $orderType);
                $message = __("No :orderType order found for this :type order.", ['orderType' => $orderType == 'buy' ? 'sell' : 'buy', 'type' => $orderType]);
                $this->logger->log('Order', $message);
                return $message;
            }
            foreach ($beingProcessingOrders as $beingProcessingOrder) {
                $price = $beingProcessingOrder->price;

                if($this->refundIfFeesZero($beingProcessingOrder,$beingProcessType,$price) && $this->refundIfFeesZero($order,$orderType,$price)){
                    $response = $this->order($order, $beingProcessingOrder, $orderType);
                } else {
                    continue;
                };
                $this->logger->log(']', "", false);
                if (!$response) {
                    break;
                }
            }
            $this->closeOrder($order, $orderType);
            $request = [];
            $request['base_coin_id'] = $order->base_coin_id;
            $request['trade_coin_id'] = $order->trade_coin_id;
            $request['dashboard_type'] = 'dashboard';
            $request['per_page'] = '';
            $time = time();
            $interval = 1440;
            $startTime = $time - 864000;
            $endTime = $time;
            $socket_data = [];
            $d_service = new DashboardService();
            $socket_data['trades'] = $d_service->getMarketTransactions((object) $request)['data'];
            $chartService = new TradingViewChartService();
            $socket_data['chart'] = $chartService->getChartData($startTime, $endTime, $interval, $order->base_coin_id, $order->trade_coin_id);
            $channel_name = 'trade-info-'.$order->base_coin_id.'-'.$order->trade_coin_id;
            $event_name = 'process';
            $socket_data['summary'] = $d_service->getOrderData((object) $request)['data'];
            $socket_data['update_trade_history'] = false;
            sendDataThroughWebSocket($channel_name,$event_name,$socket_data);
            $socket_data = $d_service->getMyTradeHistory((object) $request)['data'];
            $channel_name = 'trade-history-'.$order->base_coin_id.'-'.$order->trade_coin_id.'-'.$order->user_id;
            sendDataThroughWebSocket($channel_name,$event_name,$socket_data);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * if fees 0 then refund order
     * @param $order
     * @param $type
     * @return bool
     */
    public function refundIfFeesZero($order, $type, $price)
    {
        if ($type == 'buy') {
            $order = Buy::find($order->id);

            $amount = custom_number_format(bcsub($order->amount,$order->processed));
            if($order->taker_fees != 0){
                $buyFees = bcdiv(bcmul($price, bcmul($amount, $order->taker_fees)), 100);
            }else{
                $buyFees = 1;
            }
            if($order->maker_fees != 0){
                $sellFees = bcdiv(bcmul($price, bcmul($amount, $order->maker_fees)), 100);
            }else{
                $sellFees = 1;
            }

            $adjustValue = bcadd(bcmul($price, $amount), 0);
            $coinId = $order->base_coin_id;
        } else {
            $order = Sell::find($order->id);

            $amount = custom_number_format(bcsub($order->amount,$order->processed));
            if($order->taker_fees != 0){
                $buyFees = bcdiv(bcmul($price, bcmul($amount, $order->taker_fees)), 100);
            }else{
                $buyFees = 1;
            }
            if($order->maker_fees != 0){
                $sellFees = bcdiv(bcmul($price, bcmul($amount, $order->maker_fees)), 100);
            }else{
                $sellFees = 1;
            }
//            $buyFees = bcdiv(bcmul($price, bcmul($amount, $order->taker_fees)), 100);
//            $sellFees = bcdiv(bcmul($price, bcmul($amount, $order->maker_fees)), 100);

            $adjustValue = $amount;
            $coinId = $order->trade_coin_id;
        }

        // check if fees 0, if true then refund
        if (bccomp($buyFees,"0") === 0 || bccomp($sellFees,"0") === 0) {
            DB::beginTransaction();
            try{
                $this->logger->log('OrderProcessing', "Return for fees 0");
                $walletRepo = new UserWalletRepository(UserWallet::class);
                $wallet = $walletRepo->getDocs(['user_id' => $order->user_id, 'coin_id' => $coinId])->first();
                $wallet->increment('balance', $adjustValue);
                if($type == 'buy'){
                    $model = new Buy();
                }else{
                    $model = new Sell();
                }

                if (bccomp(visual_number_format($order->processed), "0") === 0) {
                    $model->find($order->id)->delete();
                } else {
                    $model->where(['id' => $order->id,'status' => 0])->update(['amount' => $order->processed,'status' => 1]);
                }
                DB::commit();
                $order->amount = $order->processed;

                //TODO
                /*broadcastOrderData($order, $type, 'orderRemove', $order->user_id);
                broadcastWalletData($wallet->id, $order->user_id);*/
                return false;
            }catch (\Exception $e){
                DB::rollBack();
                return false;
            }
        }else{
            return true;
        }
    }

    public function refundIfFeesZeroMarket($order, $price)
    {
            $order = Buy::find($order->id);
            $total = custom_number_format(bcsub($order->request_amount,$order->processed_request_amount));
            if($order->taker_fees != 0){
                $buyFees = bcdiv(bcmul($total, $order->taker_fees), 100);
            }else{
                $buyFees = 1;
            }
            if($order->maker_fees != 0){
                $sellFees = bcdiv(bcmul($total, $order->maker_fees), 100);
            }else{
                $sellFees = 1;
            }


            $adjustValue = $total;
            $coinId = $order->base_coin_id;

        // check if fees 0, if true then refund
        if (bccomp($adjustValue,0) !== 0 &&(bccomp($buyFees,"0") === 0 || bccomp($sellFees,"0") === 0)) {
            DB::beginTransaction();
            try{
                $this->logger->log('OrderProcessing', "Return $adjustValue for fees 0");
                $walletRepo = new UserWalletRepository(UserWallet::class);
                $wallet = $walletRepo->getDocs(['user_id' => $order->user_id, 'coin_id' => $coinId])->first();
                $wallet->increment('balance', $adjustValue);
                $model = new Buy();


                if (bccomp(visual_number_format($order->processed_request_amount), "0") === 0) {
                    $model->find($order->id)->delete();
                } else {
                    $model->where(['id' => $order->id,'status' => 0])->update(['request_amount' => $order->processed_request_amount,'status' => 1]);
                }
                DB::commit();

                //TODO
//                broadcastWalletData($wallet->id, $order->user_id);
                return false;
            }catch (\Exception $e){
                DB::rollBack();
                return false;
            }
        }else{
            return true;
        }
    }

    public function _getBeingProcessingOrders($order, $type)
    {
//        TODO:: Need to grab only needed amount
        $condition = [
            'status' => 0,
            'is_market' => 0,
            'base_coin_id' => $order->base_coin_id,
            'trade_coin_id' => $order->trade_coin_id
        ];
        if ($type == 'buy') {
            $repo = new SellOrderRepository(Sell::class);
            if ($order->is_market == 0) {
                $condition = array_merge($condition, ['price' => ['<=', $order->price]]);
            }
            return $repo->getDocs($condition, null, ['price' => 'asc']);
        } else {
            $repo = new BuyOrderRepository(Buy::class);
            if ($order->is_market == 0) {
                $condition = array_merge($condition, ['price' => ['>=', $order->price]]);
            }
            return $repo->getDocs($condition, null, ['price' => 'desc']);
        }
    }

    public function order($order, $beingProcessingOrder, $orderType)
    {
        DB::beginTransaction();
        try {
            if ($orderType == 'buy') {
                $buy = $order;
                $sell = $beingProcessingOrder;
            } else {
                $buy = $beingProcessingOrder;
                $sell = $order;
            }

            $this->sellAbleAmount = bcsub($sell->amount, $sell->processed);
            $this->buyAbleAmount = bcsub($buy->amount, $buy->processed);


            if (bccomp($this->buyAbleAmount, "0") === 0 || bccomp($this->sellAbleAmount, "0") === 0) {
                // Extra check if any Available Amount 0
                $this->logger->log('OrderProcessing', "Order processing failed(1).");
                DB::rollBack();
                return true;
            }

            $this->logger->log('OrderProcessing', "Order processing start ..................");
            $this->logger->log('OrderProcessing', "New fees rule to base coin ..................");

            $this->logger->log('OrderProcessing', "Buy ID: $buy->id OrderType: $buy->is_market Price: $buy->price Amount: $buy->amount Processed: $buy->processed Remaining: $this->buyAbleAmount");
            $this->logger->log('OrderProcessing', "Sell ID: $sell->id OrderType: $sell->ordertype Price: $sell->price Amount: $sell->amount Processed: $sell->processed Remaining: $this->sellAbleAmount");

            if (bccomp($this->buyAbleAmount, $this->sellAbleAmount) !== 1) {
                $this->amountToBeProcessed = $this->buyAbleAmount;
            } else {
                $this->amountToBeProcessed = $this->sellAbleAmount;
            }

            $this->logger->log('OrderProcessing', "Processable Amount: $this->amountToBeProcessed");

            $input = $this->_transactionArray($buy, $sell, $this->amountToBeProcessed, $order->ordertype);

            if (bccomp($this->buyAbleAmount, $this->sellAbleAmount) == -1) {
                $buy->increment('processed', $this->amountToBeProcessed, ['status' => 1]);
                $sell->increment('processed', $this->amountToBeProcessed);
            } else if (bccomp($this->buyAbleAmount, $this->sellAbleAmount) == 0) {
                $sell->increment('processed', $this->amountToBeProcessed, ['status' => 1]);
                $buy->increment('processed', $this->amountToBeProcessed, ['status' => 1]);
            } else if (bccomp($this->buyAbleAmount, $this->sellAbleAmount) == 1) {
                $buy->increment('processed', $this->amountToBeProcessed);
                $sell->increment('processed', $this->amountToBeProcessed, ['status' => 1]);
            }
            $this->logger->log('OrderProcessing', "Buy and Sell updated.");

            $this->logger->log('Transaction data', json_encode($input));

            $transaction = $this->object->create($input['input']);

            if (!$buy->is_conditioned) {
                if (!$this->_updateTradeCoinWallet($transaction, $buy,$sell, $this->amountToBeProcessed,$input['buy_fees'],$input['sell_fees'])) {
                    DB::rollBack();
                    return true;
                }
                $this->logger->log('OrderProcessing', "Wallet's Trade Coin balance updated.");
            }

            if (!$this->_updateBaseCoinWallet($sell, $transaction,$input['sell_fees'])) {
                DB::rollBack();
                if ($orderType == 'buy') {
                    $ord = Buy::find($order->id);
                } else {
                    $ord = Sell::find($order->id);
                }
                $this->closeOrder($ord, $orderType);
                return true;
            }
            $this->logger->log('OrderProcessing', "Wallet's base Coin balance updated.");

            $transactionId = isset($transactionId) ? $transactionId : time() . fixedlenstr($transaction->id);
            $this->object->updateWhere(['id' => $transaction->id], ['transaction_id' => $transactionId]);
            $this->logger->log('OrderProcessing', "Transaction updated.");

            $this->update24HourPrice($transaction);

            //affiliation history for transaction

            if (!$this->_updateCoinPairs($transaction)) {
                DB::rollBack();
                return true;
            }

            $this->_checkConditionedOrders($transaction);

            $this->logger->log('transaction -->', json_encode($transaction));

            $notifySms = __('Transaction processed successfully, Amount '.number_format($transaction->amount,4).get_coin_type($transaction->trade_coin_id).'/'.number_format($transaction->price,4).get_coin_type($transaction->base_coin_id));
            Notification::create(['user_id' => $transaction->buy_user_id, 'title' => $notifySms, 'notification_body' => $notifySms]);
            Notification::create(['user_id' => $transaction->sell_user_id, 'title' => $notifySms, 'notification_body' => $notifySms]);

            dispatch(new TradingViewChartJob($transaction))->onQueue('update-candlesticks');
            dispatch(new TransactionBroadcastJob($buy, $sell, $transaction))->onQueue('transaction-broadcast');
//            dispatch(new DistributeAffiliationBonus($transaction))->onQueue('distribute-affiliation-bonus');
            DB::commit();
            $this->logger->log('OrderProcessing', "Order processing end ..................");

            //TODO
            //dispatch(new BroadcastOrderBookBookmarkJob($transaction))->onQueue('broadcast-orderbook-bookmark');

            if (($orderType == 'buy' && $buy->status == 1) || ($orderType == 'sell' && $sell->status == 1)) {
                DB::commit();
                return false;
            }
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logger->log('OrderProcessing', "Error:" . $e->getMessage() . ' Line: ' . $e->getLine() . "\n\n");
            return true;
        }
    }
    public function update24HourPrice($transaction){
        try{
            $this->logger->log('update24HourPrice $transaction', json_encode($transaction));
            $pair = CoinPair::where(['parent_coin_id' => $transaction->base_coin_id,'child_coin_id' => $transaction->trade_coin_id])->first();
            $tData = Transaction::select(DB::raw('max(price) as max'),DB::raw('min(price) as min'),DB::raw('sum(amount) as total'))
                ->where(['base_coin_id' => $transaction->base_coin_id,'trade_coin_id' => $transaction->trade_coin_id])
                ->where('created_at', '>', Carbon::now()->subDays(1))
                ->groupBy(['base_coin_id','trade_coin_id'])
                ->first();
            $this->logger->log('CheckPrice', "Old Price: $pair->price, New Price: $transaction->price");

            $pair->change = $pair->price == 0 ? 0 :bcmul(bcdiv(bcsub($transaction->price,$pair->price),$pair->price),100);
            $pair->price = $transaction->price;
            $pair->high = $tData->max;
            $pair->low = $tData->min;
            $pair->volume = $tData->total;
            $pair->save();
            $this->logger->log('update24HourPrice $pair change', $pair->change);

            return true;
        }catch (\Exception $e){
            $this->logger->log('OrderProcessing', "24 Hour data update failed:" . $e->getMessage() . ' Line: ' . $e->getLine().' file: ' . $e->getFile() . "\n\n");
            return false;
        }

    }

    private function _transactionArray($buy, $sell, $amount, $orderType)
    {
        $input = [];
        $input['buy_id'] = $buy->id;
        $input['buy_user_id'] = $buy->user_id;

        $input['sell_id'] = $sell->id;
        $input['sell_user_id'] = $sell->user_id;

        $input['amount'] = custom_number_format($amount);
        $input['trade_coin_id'] = $sell->trade_coin_id;
        $input['base_coin_id'] = $sell->base_coin_id;


        if ($orderType == 1) {
            if ($buy->is_market == 0) {
                $input['price'] = custom_number_format($buy->price);
                $input['price_order_type'] = 'buy';
                $input['btc_rate'] = custom_number_format($buy->btc_rate);
                $input['buy_fees'] = bcdiv(bcmul($input['price'], bcmul($amount, $buy->taker_fees)), 100);
                $input['sell_fees'] = bcdiv(bcmul($input['price'], bcmul($amount, $buy->maker_fees)), 100);
                $buy_fees = custom_number_format($buy->taker_fees);
                $sell_fees = custom_number_format($buy->maker_fees);
            } else {
                $input['price'] = custom_number_format($sell->price);
                $input['price_order_type'] = 'sell';
                $input['btc_rate'] = custom_number_format($sell->btc_rate);
                $input['buy_fees'] = bcdiv(bcmul($input['price'], bcmul($amount, $sell->taker_fees)), 100);
                $input['sell_fees'] = bcdiv(bcmul($input['price'], bcmul($amount, $sell->maker_fees)), 100);
                $buy_fees = custom_number_format($sell->taker_fees);
                $sell_fees = custom_number_format($sell->maker_fees);
            }
        } else {
            if (strtotime($buy->created_at) > strtotime($sell->created_at)) {
                $input['price'] = custom_number_format($sell->price);
                $input['price_order_type'] = 'sell';
                $input['btc_rate'] = custom_number_format($sell->btc_rate);
                $input['buy_fees'] = bcdiv(bcmul($input['price'], bcmul($amount, $buy->taker_fees)), 100);
                $input['sell_fees'] = bcdiv(bcmul($input['price'], bcmul($amount, $sell->maker_fees)), 100);
                $buy_fees = custom_number_format($buy->taker_fees);
                $sell_fees = custom_number_format($sell->maker_fees);
            } else {
                $input['price'] = custom_number_format($buy->price);
                $input['price_order_type'] = 'buy';
                $input['btc_rate'] = custom_number_format($buy->btc_rate);
                $input['buy_fees'] = bcdiv(bcmul($input['price'], bcmul($amount, $buy->maker_fees)), 100);
                $input['sell_fees'] = bcdiv(bcmul($input['price'], bcmul($amount, $sell->taker_fees)), 100);
                $buy_fees = custom_number_format($buy->maker_fees);
                $sell_fees = custom_number_format($sell->taker_fees);
            }
        }

        $input['buy_fees_in_base_coin'] = $input['buy_fees'];
        $input['sell_fees_in_base_coin'] = $input['sell_fees'];


        $input['total'] = bcmul($amount, $input['price']);
        $input['btc'] = bcmul($amount, $input['btc_rate']);
        return ['input' => $input, 'buy_fees' => $buy_fees, 'sell_fees' => $sell_fees];
    }

    /**
     * update trade coin wallet after transaction
     * @param $transaction
     * @param $buy
     * @param $sell
     * @param $amount
     * @param $buyFees
     * @param $sellFees
     * @return bool
     */
    public function _updateTradeCoinWallet($transaction, $buy, $sell, $amount, $buyFees, $sellFees)
    {
        try {
            $this->logger->log('OrderProcessing', "Start update user coin balance ..................");
            $walletRepo = new UserWalletRepository(UserWallet::class);

            $tradeCoin = Coin::find($buy->trade_coin_id);
            $buyerTradeCoinWallet = $walletRepo->getDocs(['user_id' => $buy->user_id, 'coin_type' => $tradeCoin->coin_type])->first();
            $this->logger->log('OrderProcessing', "trade coin wallet data..................");
            $this->logger->log('OrderProcessing', json_encode($buyerTradeCoinWallet));
            $this->logger->log('OrderProcessing', "Buy User Coin Balance Before Update: " . $buyerTradeCoinWallet->balance);
            $updateBuyerTradeCoinWallet = $buyerTradeCoinWallet->increment('balance', $amount);
            if (empty($updateBuyerTradeCoinWallet)) {
                $this->logger->log('OrderProcessing', "Buyer coin wallet update failed.");
                return false;
            }
            $this->logger->log('OrderProcessing', "Buy User Coin Balance After Update:" . $buyerTradeCoinWallet->balance);

            //refund if buy price is big than sell
            $baseCoin = Coin::find($buy->base_coin_id);
            $buyerBaseCoinWallet = $walletRepo->getDocs(['user_id' => $buy->user_id, 'coin_type' => $baseCoin->coin_type])->first();
            $this->logger->log('OrderProcessing', "Buy User Base Coin Balance Before Adjust: " . $buyerBaseCoinWallet->balance);

            $buyTotalOld = bcadd(bcmul($buy->price, $amount), bcmul(bcmul(bcmul($buy->price, $amount), $buy->taker_fees),"0.01"));
            //$buyTotalNew = bcadd(bcmul($transaction->price, $amount), $buyFees);
            $buyTotalNew = bcadd(bcmul($transaction->price, $amount),bcmul(bcmul(bcmul($transaction->price, $amount), $buyFees),"0.01"));
            $this->logger->log('OrderProcessing', "buyTotalOld  :".$buyTotalOld);
            $this->logger->log('OrderProcessing', "buyTotalNew  :".$buyTotalNew);
            $this->logger->log('OrderProcessing', "buyFees  :".$buyFees);
            $this->logger->log('OrderProcessing', "sellFees  :".$sellFees);

            $adjustValue = bcsub($buyTotalOld, $buyTotalNew);
            $this->logger->log('OrderProcessing', "Adjustment Value :".$adjustValue." that will refunded or deduct");
            $updateBuyerBaseCoinWallet = $walletRepo->getDocs(['user_id' => $buy->user_id, 'coin_type' => $baseCoin->coin_type])->first();
            $this->logger->log('Update Object', ">>>>>>>".json_encode($updateBuyerBaseCoinWallet));
            if(bccomp($adjustValue,0) !== 0){
                $isUpdateBalance = $updateBuyerBaseCoinWallet->increment('balance', $adjustValue);
                if (empty($isUpdateBalance)) {
                    $this->logger->log('OrderProcessing', "Buyer Base coin wallet Adjust failed. >>>".$isUpdateBalance);
                    $this->logger->log('OrderProcessing>>>>',"user_id => ".$buy->user_id. "coin_id => ".$buy->base_coin_id);
                    return false;
                }
            }
            $this->logger->log('OrderProcessing', "Buy User Base Coin Balance After Adjust: " . $buyerBaseCoinWallet->balance);
        } catch (\Exception $e) {
            $this->logger->log('OrderProcessing', "Update coin wallet failed. Message: " . $e->getMessage() . " Line: " . $e->getLine() . " File: " . $e->getFile());
            return false;
        }
        return true;
    }

    /**
     * update base coin wallet after transaction
     * @param $sell
     * @param $transaction
     * @param $sell_fees
     * @return bool
     */
    public function _updateBaseCoinWallet($sell, $transaction, $sell_fees)
    {
        try {
            $this->logger->log('OrderProcessing', "Start update user base coin balance ..................");
            $walletRepo = new UserWalletRepository(UserWallet::class);

            $baseCoin = Coin::find($sell->base_coin_id);
            $sellerDeposit = $walletRepo->getDocs(['user_id' => $sell->user_id, 'coin_type' => $baseCoin->coin_type])->first();

            $this->logger->log('OrderProcessing', "Sell User BaseCoin Balance Before Update: " . $sellerDeposit->balance);
            $updateSellBalance = bcsub($transaction->total, bcmul(bcmul($transaction->total, $sell_fees), "0.01"));
            if($updateSellBalance == 0){
                return false;
            }
            $updateSellerBaseCoinWallet = $sellerDeposit->increment('balance', $updateSellBalance);
            if (empty($updateSellerBaseCoinWallet)) {
                $this->logger->log('OrderProcessing', "Seller base coin wallet update failed.");
                return false;
            }
            $this->logger->log('OrderProcessing', "Sell User BaseCoin Balance After Update: " . $sellerDeposit->balance);

        } catch (\Exception $e) {
            $this->logger->log('OrderProcessing', "Update base coin wallet failed. Message: " . $e->getMessage() . " Line: " . $e->getLine() . " File: " . $e->getFile());
            return false;
        }
        return true;
    }

    public function closeOrder($order, $orderType)
    {
        DB::beginTransaction();
        try {
            $log = app(Logger::class);
            $log->log('OrderClose', json_encode($order));
            $log->log('OrderClose', $orderType);
            if ($order->is_market == 1 && $order->status == 0 && bccomp($order->amount, $order->processed)) {
                $amount = bcsub($order->amount, $order->processed);
                if ($orderType == 'buy') {
                    $userFees = calcualte_fee_for_user($order->user_id);
                    $adjustValue = bcadd(bcmul($order->price, $amount), bcmul(bcmul(bcmul($order->price, $amount), $userFees),"0.01"));
                    $coinId = $order->base_coin_id;
                } else {
                    $adjustValue = $amount;
                    $coinId = $order->trade_coin_id;
                }
                $walletRepo = new UserWalletRepository(UserWallet::class);
                $wallet = $walletRepo->getDocs(['user_id' => $order->user_id, 'coin_id' => $coinId])->first();
                $wallet->increment('balance', $adjustValue);
                if (bccomp(visual_number_format($order->processed), "0") == 0) {
                    $order->delete();
                } else {
                    $order->amount = $order->processed;
                    $order->status = 1;
                    $order->update();
                }

            } else if ($order->is_market == 1 && $order->status == 0 && $order->amount == $order->processed) {
                $order->status = 1;
                $order->update();
                $log->log('OrderServiceException', "Update order status 0 to 1.");
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $log = app(Logger::class);
            $log->log('OrderServiceException', "Update order failed. Message: " . $e->getMessage() . " Line: " . $e->getLine() . " File: " . $e->getFile());
        }
    }

    /**
     * update coin pairs data after transaction such as last price, change, high, low
     * @param $transaction
     * @return bool
     */
    public function _updateCoinPairs($transaction)
    {
        try {
            $repo = new CoinPairRepository(CoinPair::class);
            $coins = $repo->getDocs(['parent_coin_id' => $transaction->base_coin_id, 'child_coin_id' => $transaction->trade_coin_id])->first();
            $this->logger->log('CheckPrice', "Old Price: $coins->price, New Price: $transaction->price");
            if ($coins->price != $transaction->price) {
                $repo->updateWhere(['parent_coin_id' => $transaction->base_coin_id, 'child_coin_id' => $transaction->trade_coin_id], ['price' => $transaction->price]);

                //TODO
                dispatch(new StopLimitProcessJob($coins))->onQueue('stop-limit');
            }
            return true;
        } catch (\Exception $e) {
            $this->logger->log('UpdateCoinPriceException', $e->getMessage());
            return false;
        }
    }

    private function _checkConditionedOrders($transaction)
    {
        $this->logger->log('CheckConditionBuyOrder', 'Start....');
        $buyOrderRepo = new BuyOrderRepository(Buy::class);
        $buyOrder = $buyOrderRepo->getById($transaction->buy_id);

        $this->logger->log('CheckBuyOrder', 'Start....');

        if (!empty($buyOrder) && $buyOrder->status == 1 && $buyOrder->is_conditioned) {
            $this->logger->log('CheckBuyOrder', 'Dispatch buy job....');
        }

        $this->logger->log('CheckBuyOrder', 'End....');
        $sellOrderRepo = new SellOrderRepository(Sell::class);
        $sellOrder = $sellOrderRepo->getById($transaction->sell_id);

        $this->logger->log('CheckSellOrder', 'Start....');
        if (!empty($sellOrder) && $sellOrder->status == 1 && $sellOrder->is_conditioned) {
            $unprocessedSellOrders = $sellOrderRepo->getDocs(['condition_buy_id' => $sellOrder->condition_buy_id, 'status' => 0]);
            if (count($unprocessedSellOrders) == 0) {
                $stopLimitRepo = new StopLimitRepository(StopLimit::class);
                $stopLimit = $stopLimitRepo->getDocs(['condition_buy_id' => $sellOrder->condition_buy_id, 'status' => 0]);

                $this->logger->log('CheckStopLimit', json_encode($stopLimit));
                $stopLimit = $stopLimit->first();
                if (!empty($stopLimit)) {
                    $this->logger->log('CheckSellOrder', 'Delete Stop Limit.....');
                    $stopLimit->delete();
                }
            }
        }
        $this->logger->log('CheckSellOrder', 'End....');
    }

    public function getMyTradeHistory($baseCoinId, $tradeCoinId, $userId, $orderType = null, $duration = null)
    {
        $select = ['transaction_id',DB::raw("CASE WHEN buy_user_id =".getUserId()." THEN buy_fees WHEN sell_user_id =".getUserId()." THEN sell_fees END as fees"),DB::raw("visualNumberFormat(amount) as amount"), DB::raw("visualNumberFormat(price) as price"), DB::raw("visualNumberFormat(last_price) as last_price"),'price_order_type', DB::raw("visualNumberFormat(total) as total"), 'created_at', DB::raw("TIME(created_at) as time")];
        $where = [
            'base_coin_id' => $baseCoinId,
            'trade_coin_id' => $tradeCoinId,
        ];
        $time = 0;
        $orWhere = [];
        if(Auth::guard('api')->check()) {

            if (empty($orderType)) {
            $where['buy_user_id'] = $userId;
            $orWhere = [
                'sell_user_id' => $userId,
                'base_coin_id' => $baseCoinId,
                'trade_coin_id' => $tradeCoinId,
            ];

        } else {
                if ($orderType == 'buy') {
                    $where['buy_user_id'] = $userId;
                    $select[] = 'buy_fees as fees';
                } else {
                    $where['sell_user_id'] = $userId;
                    $select[] = 'sell_fees as fees';
                }
            }
        }else{
            if (empty($orderType)) {
                $where['buy_user_id'] = 0;
                $orWhere = [
                    'sell_user_id' => 0,
                    'base_coin_id' => $baseCoinId,
                    'trade_coin_id' => $tradeCoinId,
                ];

            } else {
                if ($orderType == 'buy') {
                    $where['buy_user_id'] = 0;
                    $select[] = 'buy_fees as fees';
                } else {
                    $where['sell_user_id'] = 0;
                    $select[] = 'sell_fees as fees';
                }
            }
        }

        if (!empty($duration) || ($duration != 0)) {
            $time = Carbon::now()->subDays($duration);
        }

        return $this->object->getMyTradeHistory($select, $where, $orWhere, $time);
    }

    public function getMyAllTransactionHistory($userId)
    {
        $select = ['transaction_id',DB::raw("CASE WHEN buy_user_id =".Auth::id()." THEN buy_fees WHEN sell_user_id =".Auth::id()." THEN sell_fees END as fees"),
            DB::raw("visualNumberFormat(amount) as amount"),
            DB::raw("bc.coin_type as base_coin"),
            DB::raw("tc.coin_type as trade_coin"),
            DB::raw("visualNumberFormat(price) as price"),
            DB::raw("visualNumberFormat(last_price) as last_price"),
            'price_order_type',
            DB::raw("visualNumberFormat(total) as total"),
            DB::raw("transactions.created_at as time")
        ];
        $where = [
        ];
        $time = 0;
        $where['buy_user_id'] = $userId;
        $orWhere = [
            'sell_user_id' => $userId,
        ];

        return $this->object->getMyAllTradeHistory($select, $where, $orWhere);
    }



    public function getAllTradeHistory($baseCoinId, $tradeCoinId)
    {
        $where = [
            'base_coin_id' => $baseCoinId,
            'trade_coin_id' => $tradeCoinId,
        ];

        return $this->object->getAllTradeHistory($where);
    }

}
