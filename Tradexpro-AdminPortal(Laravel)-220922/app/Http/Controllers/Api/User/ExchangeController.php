<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Services\CoinPairService;
use App\Http\Services\DashboardService;
use App\Http\Services\Logger;
use App\Http\Services\TradingViewChartService;
use App\Model\AdminSetting;
use http\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExchangeController extends Controller
{
    private $service;
    private $coinPairService;
    private $logger;
    public function __construct()
    {
        $this->service = new DashboardService();
        $this->coinPairService = new CoinPairService();
        $this->logger = new Logger();
    }

    /**
     * specific exchange dashboard data
     * @param Request $request
     * @param $pair
     * @return array
     */
    public function appExchangeDashboard(Request $request, $pair=null){
        $data['title'] = __('Exchange');

        if(isset($pair)) {
            $ar =  explode('_',$pair);
            if (empty($request->base_coin_id) || empty($request->trade_coin_id)) {
                $tradeCoinId = get_coin_id($ar[0]);
                $baseCoinId = get_coin_id($ar[1]);
                $request->merge([
                    'base_coin_id' => $baseCoinId,
                    'trade_coin_id' => $tradeCoinId,
                ]);
            }
        } else {
            $request->merge([
                'base_coin_id' => get_default_base_coin_id(),
                'trade_coin_id' => get_default_trade_coin_id(),
            ]);
        }

        $request->merge([
            'dashboard_type' => 'dashboard'
        ]);
        $pairservice = new CoinPairService();
        $data['pairs'] = $pairservice->getAllCoinPairs()['data'];
        $data['order_data'] = $this->service->getOrderData($request)['data'];
        $data['fees_settings'] = $this->userFeesSettings();
        $data['last_price_data'] = $this->service->getDashboardMarketTradeDataTwo($request->base_coin_id, $request->trade_coin_id,2);
        $data['broadcast_port'] = env('BROADCAST_PORT');
        $data['app_key'] = env('PUSHER_APP_KEY');
        $data['cluster'] = env('PUSHER_APP_CLUSTER');

        return $data;
    }

    // get fees settings
    public function userFeesSettings()
    {
        if(Auth::guard('api')->check())  {
            $fees = calculated_fee_limit(getUserId());
        } else {
            $fees = [];
        }
        return $fees;
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExchangeAllOrdersApp(Request $request){
        $data = [
            'success' => false,
            'data' => [],
            'message'=>__('Something went wrong')
        ];
        try {
            $response = $this->service->getOrders($request)['data'];
            $data = [
                'success' => true,
                'data' => $response,
                'message' => 'All Orders'
            ];
            return response()->json($data);
        } catch (\Exception $e) {
            $this->logger->log('getExchangeAllOrders', $e->getMessage());
            return response()->json($data);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExchangeAllBuyOrdersApp(Request $request)
    {
        $response = [
            'success' => false,
            'data' => [],
            'message'=>__('Something went wrong')
        ];
        try {
            $data['title'] = __('All Open Buy Order History of '.$request->trade_coin_type.'/'.$request->base_coin_type);
            $data['type'] = 'buy';
            $data['sub_menu'] = 'buy_order';
            $data['tradeCoinId'] = get_coin_id($request->trade_coin_type);
            $data['baseCoinId'] = get_coin_id($request->base_coin_type);
            $data['items'] = $this->service->getOrders($request)['data']['orders'];
            $response = [
                'success' => true,
                'data' => $data,
                'message' => 'All Buy Orders'
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            $this->logger->log('getExchangeAllBuyOrdersApp', $e->getMessage());
            return response()->json($response);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExchangeAllSellOrdersApp(Request $request)
    {
        $response = [
            'success' => false,
            'data' => [],
            'message'=>__('Something went wrong')
        ];
        try {
            $data['title'] = __('All Open Sell Order History of '.$request->trade_coin_type.'/'.$request->base_coin_type);
            $data['type'] = 'sell';
            $data['sub_menu'] = 'buy_order';
            $data['tradeCoinId'] = get_coin_id($request->trade_coin_type);
            $data['baseCoinId'] = get_coin_id($request->base_coin_type);
            $data['items'] = $this->service->getOrders($request)['data']['orders'];
            $response = [
                'success' => true,
                'data' => $data,
                'message' => 'All Sell Orders'
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            $this->logger->log('getExchangeAllSellOrdersApp', $e->getMessage());
            return response()->json($response);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExchangeMarketTradesApp(Request $request)
    {
        $data = [
            'success' => false,
            'data' => [],
            'message'=>__('Something went wrong')
        ];
        try {
            $response = $this->service->getMarketTransactions($request)['data'];
            $data = [
                'success' => true,
                'data' => $response,
                'message'=>'All Market Trades'
            ];
            return response()->json($data);
        } catch (\Exception $e) {
            $this->logger->log('getExchangeMarketOrders', $e->getMessage());
            return response()->json($data);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyExchangeOrdersApp(Request $request)
    {
        $data = [
            'success' => false,
            'data' => [],
            'message'=>__('Something went wrong')
        ];
        try {
            $response = $this->service->getMyOrders($request)['data'];
            $data = [
                'success' => true,
                'data' => $response,
                'message' => __('My Exchange Orders')
            ];
            return response()->json($data);
        } catch (\Exception $e) {
            $this->logger->log('getMyExchangeOrders', $e->getMessage());
            return response()->json($data);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyExchangeTradesApp(Request $request)
    {
        $data = [
            'success' => false,
            'data' => [],
            'message'=>__('Something went wrong')
        ];
        try {
            $response = $this->service->getMyTradeHistory($request)['data'];
            $data = [
                'success' => true,
                'data' => $response,
                'message' => __('My Exchange Trades')
            ];
            return response()->json($data);
        } catch (\Exception $e) {
            $this->logger->log('getMyExchangeTrades', $e->getMessage());
            return response()->json($data);
        }
    }

    public function getExchangeChartDataApp(Request $request){
        $service = new DashboardService();
        if (empty($request->base_coin_id) || empty($request->trade_coin_id)) {
            $tradeCoinId = $service->_getTradeCoin();
            $baseCoinId = $service->_getBaseCoin();
            $request->merge([
                'base_coin_id' => $baseCoinId,
                'trade_coin_id' => $tradeCoinId,
            ]);
        }
        $interval = $request->input('interval', 1440);
        $baseCoinId = $request->base_coin_id;
        $tradeCoinId = $request->trade_coin_id;
        $startTime = $request->input('start_time', strtotime(now()) - 864000);
        $endTime = $request->input('end_time', strtotime(now()));
        $chartService = new TradingViewChartService();
        if($startTime >= $endTime){
            return response()->json([
                'success' => false,
                'message' => __('start.time.is.always.big.than.end.time')
            ]);
        }
        $data = $chartService->getChartData($startTime, $endTime, $interval, $baseCoinId, $tradeCoinId);

        $response = [
            'success' => true,
            'message' => __('Success'),
            'dataType' => 'own',
            'data' => $data
        ];
        return $response;

    }


    public function deleteMyOrderApp(Request $request)
    {
        $type = $request->type;
        $id = $request->id;
        $dashboardService = new DashboardService();
        $response = $dashboardService->deleteOrder($id, $type);

        return response()->json($response);
    }

}
