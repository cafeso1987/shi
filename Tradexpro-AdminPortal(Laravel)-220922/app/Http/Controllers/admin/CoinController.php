<?php

namespace App\Http\Controllers\admin;

use App\Http\Repositories\AffiliateRepository;
use App\Http\Requests\Admin\CoinRequest;
use App\Http\Requests\Admin\CoinSaveRequest;
use App\Http\Requests\Admin\CoinSettingRequest;
use App\Http\Requests\Admin\GiveCoinRequest;
use App\Http\Services\CoinPaymentsAPI;
use App\Http\Services\CoinService;
use App\Http\Services\CoinSettingService;
use App\Http\Services\CurrencyService;
use App\Http\Services\Logger;
use App\Jobs\AdjustWalletJob;
use App\Jobs\NewCoinCreateJob;
use App\Model\AdminGiveCoinHistory;
use App\Model\BuyCoinHistory;
use App\Model\Coin;
use App\Model\Wallet;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CoinController extends Controller
{
    private $coinService;
    private $coinSettingService;
    private $logger;
    public function __construct()
    {
        $this->coinService = new CoinService();
        $this->logger = new Logger();
        $this->coinSettingService = new CoinSettingService();
    }


    // all coin list
    public function adminCoinList(Request $request)
    {
        try {
            $data['title'] = __('Coin List');
            $data['coins'] = Coin::where('status', '<>', STATUS_DELETED)->orderBy('id','asc')->get();

            return view('admin.coin-order.coin', $data);
        } catch (\Exception $e) {
            $this->logger->log('admin coin-list exception --> ',$e->getMessage());
            return redirect()->back()->with('dismiss', $e->getMessage());
        }
    }

    // change coin status
    public function adminCoinStatus(Request $request)
    {
        $coin = Coin::find($request->active_id);
        if ($coin) {
            if ($coin->status == STATUS_ACTIVE) {
               $coin->update(['status' => STATUS_DEACTIVE]);
            } else {
                $coin->update(['status' => STATUS_ACTIVE]);
            }
            return response()->json(['message'=>__('Status changed successfully')]);
        } else {
            return response()->json(['message'=>__('Coin not found')]);
        }
    }

    // edit coin
    public function adminCoinEdit($id)
    {
        $coinId = decryptId($id);

        if(is_array($coinId)) {
            return redirect()->back()->with(['dismiss' => __('Coin not found')]);
        }

        $item = $this->coinService->getCoinDetailsById($coinId);

        if (isset($item) && $item['success'] == false) {
            return redirect()->back()->with(['dismiss' => $item['message']]);
        }

        $data['item'] = $item['data'];
        $data['title'] = __('Update Coin');
        $data['button_title'] = __('Update');

        return view('admin.coin-order.edit_coin', $data);
    }


//    coin save process
    public function adminCoinSaveProcess(CoinRequest $request) {
        try {
            $coin_id = '';
            $input['coin_type'] = $request->coin_type;
            $input['network'] = $request->network;
            $input['name'] = $request->name;
            $input['coin_price'] = $request->coin_price;
            $input['is_deposit'] = isset($request->is_deposit) ? 1 : 0;
            $input['is_withdrawal'] = isset($request->is_withdrawal) ? 1 : 0;
            $input['status'] = isset($request->status) ? 1 : 0;
            $input['trade_status'] = isset($request->trade_status) ? 1 : 0;
            $input['is_wallet'] = isset($request->is_wallet) ? 1 : 0;
            $input['is_buy'] = isset($request->is_buy) ? 1 : 0;
            $input['is_virtual_amount'] = isset($request->is_virtual_amount) ? 1 : 0;
            $input['is_currency'] = isset($request->is_currency) ? 1 : 0;
            $input['is_transferable'] = isset($request->is_transferable) ? 1 : 0;
            $input['minimum_buy_amount'] = $request->minimum_buy_amount;
            $input['minimum_sell_amount'] = $request->minimum_sell_amount;
            $input['minimum_withdrawal'] = $request->minimum_withdrawal;
            $input['maximum_withdrawal'] = $request->maximum_withdrawal;
            $input['withdrawal_fees'] = $request->withdrawal_fees;
            $input['max_send_limit'] = $request->max_send_limit ?? 0;

            if (!empty($request->coin_icon)) {
                $icon = uploadFile($request->coin_icon,IMG_ICON_PATH,'');
                if ($icon != false) {
                    $input['coin_icon'] = $icon;
                }
            }

            if($request->coin_id) {
                $coin_id = decryptId($request->coin_id);
            }

            $coin = $this->coinService->addCoin($input, $coin_id);

            return (isset($coin) && $coin['success']) ? redirect()->back()->with(['success' => $coin['message']]) :
                redirect()->back()->with(['dismiss' => $coin['message']]);
        } catch (\Exception $e) {
            storeException('coin_price', $e->getMessage());
            redirect()->back()->with(['dismiss' => __('Something went wrong')]);
        }
    }

    // add coin page
    public function adminAddCoin()
    {
        $data['title'] = __('Add New Coin');
        $data['button_title'] = __('Save');

        return view('admin.coin-order.add_coin', $data);
    }

    // admin new coin save process
    public function adminSaveCoin(CoinSaveRequest $request)
    {
        try {
            $save = Coin::create([
                'name' => $request->name,
                'coin_type' => strtoupper($request->coin_type),
                'network' => $request->network,
                'coin_price' => $request->coin_price,
            ]);
            if ($save) {
//                dispatch(new NewCoinCreateJob($save))->onQueue('default');

                return redirect()->route('adminCoinList')->with('dismiss', __('New coin added successfully'));
            }
            return redirect()->back()->with('dismiss', __('Something went wrong'));
        } catch (\Exception $e) {
            $this->logger->log('adminSaveCoin : ',$e->getMessage());
            return redirect()->back()->with('dismiss', __('Something went wrong'));
        }
    }

    // edit coin settings
    public function adminCoinSettings($id)
    {
        try {
            $coinId = decryptId($id);
            if(is_array($coinId)) {
                return redirect()->back()->with(['dismiss' => __('Coin not found')]);
            }
            $item = $this->coinSettingService->getCoinSettings($coinId);
            if (isset($item) && $item['success'] == false) {
                return redirect()->back()->with(['dismiss' => $item['message']]);
            }
            $data['item'] = $item['data'];
            $data['title'] = __('Update Coin Setting');
            $data['button_title'] = __('Update Setting');
            if ($item['data']->network == COIN_PAYMENT) {
                return redirect()->route('adminCoinApiSettings', ['tab' => 'payment']);
            } else {
                return view('admin.coin-order.edit_coin_settings', $data);
            }
        } catch (\Exception $e) {
            storeException('adminCoinSettings',$e->getMessage());
            return redirect()->back()->with('dismiss',__('Something went wrong'));
        }
    }

    // admin save coin setting
    public function adminSaveCoinSetting(CoinSettingRequest $request)
    {
        try {
            $response = $this->coinSettingService->updateCoinSetting($request);
            if ($response['success'] == true) {
                return redirect()->back()->with('success', $response['message']);
            } else {
                return redirect()->back()->with('dismiss', $response['message']);
            }
        } catch (\Exception $e) {
            storeException('adminSaveCoinSetting', $e->getMessage());
            return redirect()->back()->with('dismiss', __('Something went wrong'));
        }
    }

    // admin bitgo wallet adjust
    public function adminAdjustBitgoWallet($id)
    {
        try {
            $coinId = decryptId($id);
            if(is_array($coinId)) {
                return redirect()->back()->with(['dismiss' => __('Coin not found')]);
            }
            $response = $this->coinSettingService->adjustBitgoWallet($coinId);
            if ($response['success'] == true) {
                return redirect()->back()->with('success', $response['message']);
            } else {
                return redirect()->back()->with('dismiss', $response['message']);
            }
        } catch (\Exception $e) {
            storeException('adminAdjustBitgoWallet', $e->getMessage());
            return redirect()->back()->with('dismiss', __('Something went wrong'));
        }
    }

    public function adminCoinRate(){
        $currency = new CurrencyService();
        $response = $currency->updateCoinRate();
        if($response["success"])
            return redirect()->back()->with("success",$response["message"]);
        return redirect()->back()->with("dismiss",$response["message"]);
    }

    // admin coin delete
    public function adminCoinDelete($id)
    {
        try {
            $coinId = decryptId($id);
            if(is_array($coinId)) {
                return redirect()->back()->with(['dismiss' => __('Coin not found')]);
            }
            $response = $this->coinService->adminCoinDeleteProcess($coinId);
            if ($response['success'] == true) {
                return redirect()->back()->with('success', $response['message']);
            } else {
                return redirect()->back()->with('dismiss', $response['message']);
            }
        } catch (\Exception $e) {
            storeException('adminCoinDelete', $e->getMessage());
            return redirect()->back()->with('dismiss', __('Something went wrong'));
        }
    }
}
