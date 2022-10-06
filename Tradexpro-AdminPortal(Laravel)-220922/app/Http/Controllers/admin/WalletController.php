<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SendCoinBalanceRequest;
use App\Http\Services\WalletService;
use App\Model\AdminSendCoinHistory;
use App\Model\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    private $service;
    function __construct()
    {
        $this->service =  new WalletService();
    }
    // all personal wallet list
    public function adminWalletList(Request $request)
    {
        $data['title'] = __('Pocket List');
        $data['sub_menu'] = __('personal');
        if($request->ajax()){
            $data['wallets'] = Wallet::join('users','users.id','=','wallets.user_id')
                ->join('coins', 'coins.id', '=', 'wallets.coin_id')
                ->where(['wallets.type'=>PERSONAL_WALLET, 'coins.status' => STATUS_ACTIVE])
                ->orderBy('wallets.id', 'desc')
                ->select(
                    'wallets.name'
                    ,'wallets.coin_type'
                    ,'wallets.balance'
                    ,'wallets.created_at'
                    ,'users.first_name'
                    ,'users.last_name'
                    ,'users.email'
                );

            return datatables()->of($data['wallets'])
                ->addColumn('user_name',function ($item){return $item->first_name.' '.$item->last_name;})
                ->addColumn('created_at', function ($item) {return $item->created_at;})
                ->make(true);
        }

        return view('admin.wallet.index',$data);
    }

    // send coin wallet list
    public function adminWalletSendList(Request $request)
    {
        $data['title'] = __('Send Coin List');
        $data['sub_menu'] = __('send_coin_list');
        if($request->ajax()){
            $data['wallets'] = AdminSendCoinHistory::join('wallets','wallets.id','=','admin_send_coin_histories.wallet_id')
                ->join('users', 'users.id', '=', 'admin_send_coin_histories.user_id')
                ->orderBy('admin_send_coin_histories.id', 'desc')
                ->select(
                    'wallets.name'
                    ,'wallets.coin_type'
                    ,'wallets.balance'
                    ,'admin_send_coin_histories.created_at'
                    ,'users.first_name'
                    ,'users.last_name'
                    ,'users.email',
                    'admin_send_coin_histories.amount'
                );

            return datatables()->of($data['wallets'])
                ->addColumn('user_name',function ($item){return $item->first_name.' '.$item->last_name;})
                ->addColumn('created_at', function ($item) {return $item->created_at;})
//                ->addColumn('updated_by', function ($item) {return $item->author->first_name;})
                ->make(true);
        }

        return view('admin.wallet.send_coin_history',$data);
    }

    // send user wallet balance
    public function adminSendWallet()
    {
        $data['title'] = __('Send Wallet Balance');
        $data['sub_menu'] = __('send_wallet');
        $data['wallets'] = Wallet::join('users','users.id', '=', 'wallets.user_id')
            ->where(['users.role' => USER_ROLE_USER])
            ->select('users.first_name','users.last_name','users.email','wallets.*')
            ->get();

        return view('admin.wallet.send_wallet_coin', $data);
    }

    // admin send coin process
    public function adminSendBalanceProcess(SendCoinBalanceRequest $request)
    {
        $response = $this->service->sendCoinBalanceToUser($request);
        if($response['success'] == true) {
            return redirect()->back()->with('success', $response['message']);
        } else {
            return redirect()->back()->with('dismiss', $response['message']);
        }
    }

}
