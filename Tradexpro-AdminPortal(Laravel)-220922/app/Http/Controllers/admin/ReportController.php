<?php

namespace App\Http\Controllers\admin;

use App\Http\Services\BuyOrderService;
use App\Http\Services\SellOrderService;
use App\Http\Services\StopLimitService;
use App\Http\Services\TransactionService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /*
  *
  * All Stop Limit Orders History
  * adminAllOrdersHistoryStopLimit
  *
  * Show the list of specified resource.
  * @return \Illuminate\Http\Response
  *
  */
    public function adminAllOrdersHistoryStopLimit(Request $request)
    {
        $data['title'] = __('Stop Limit Order History');
        $service = new StopLimitService();
        $data['type'] = 'stop_limit';
        $data['sub_menu'] = 'stop_limit';

        if ($request->ajax()) {

            $data['items'] = $service->getOrders();

            return datatables($data['items'])
                ->addColumn('order_type', function ($item) {
                    return ucfirst($item->order_type);
                })
                ->make(true);
        }

        return view('admin.exchange.report.stop_limit_order_report',$data);
    }
  /*
  *
  * All Buy Orders History
  * adminAllOrdersHistoryBuy
  *
  * Show the list of specified resource.
  * @return \Illuminate\Http\Response
  *
  */
    public function adminAllOrdersHistoryBuy(Request $request)
    {
        $data['title'] = __('Buy Order History');
        $buyService = new BuyOrderService();
        $data['type'] = 'buy';
        $data['sub_menu'] = 'buy_order';

        if ($request->ajax()) {

            $data['items'] = $buyService->getOrders();

            return datatables($data['items'])
                ->editColumn('is_market', function ($item) {
                    return $item->is_market ? 'Market' : 'Normal';
                })
                ->editColumn('status', function ($item) {
                    if($item->status == 1) {
                        return __('Success');
                    } elseif($item->deleted_at != null) {
                        return __('Processing');
                    } elseif($item->status == 0) {
                        return __('Pending');
                    } else {
                        return __('Deleted');
                    }
                })
                ->make(true);
        }

        return view('admin.exchange.report.buy_order_report',$data);
    }

    /*
   *
   * All Sell Orders History
   * adminAllOrdersHistorySell
   *
   * Show the list of specified resource.
   * @return \Illuminate\Http\Response
   *
   */
    public function adminAllOrdersHistorySell(Request $request)
    {
        $data['title'] = __('Sell Order History');
        $data['type'] = 'sell';
        $data['sub_menu'] = 'sell_order';
        $sellService = new SellOrderService();

        if ($request->ajax()) {
            $data['items'] = $sellService->getOrders();

            return datatables($data['items'])
                ->editColumn('is_market', function ($item) {
                    return $item->is_market ? 'Market' : 'Normal';
                })
                ->editColumn('status', function ($item) {
                    if($item->status == 1) {
                        return __('Success');
                    } elseif($item->deleted_at != null) {
                        return __('Processing');
                    } elseif($item->status == 0) {
                        return __('Pending');
                    } else {
                        return __('Deleted');
                    }
                })
                ->make(true);
        }

        return view('admin.exchange.report.sell_order_report',$data);
    }

    /*
   *
   * All Sell buy transaction Orders History
   * adminAllTransactionHistory
   *
   * Show the list of specified resource.
   * @return \Illuminate\Http\Response
   *
   */
    public function adminAllTransactionHistory(Request $request)
    {
        $data['title'] = __('Transaction History');
        $data['sub_menu'] = 'transaction';
        $sellService = new TransactionService();

        if ($request->ajax()) {
            $data['items'] = $sellService->getOrders();

            return datatables($data['items'])
                ->make(true);
        }

        return view('admin.exchange.report.transaction_report',$data);
    }
}
