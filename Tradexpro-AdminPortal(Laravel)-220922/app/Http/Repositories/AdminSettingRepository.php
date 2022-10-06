<?php

namespace App\Http\Repositories;


use App\Model\AdminSetting;
use App\Model\CoinPair;

class AdminSettingRepository
{
    public function updateOrCreate($slug, $value)
    {
        return AdminSetting::updateOrCreate(['slug' => $slug], ['slug' => $slug, 'value' => $value]);
    }
    public function updateOrCreateTrade($slug, $value)
    {
        return AdminSetting::updateOrCreate(['slug' => $slug], $value);
    }


    public function ApiCredentialsUpdateOrCreate($coinId, $apiService, $withdrawalFeeMethod, $withdrawalFeePercent, $withdrawalFeeFixed)
    {
        return CoinSetting::updateOrCreate(['coin_id' => $coinId], ['coin_id' => $coinId, 'api_service' => $apiService, 'withdrawal_fee_method' => $withdrawalFeeMethod, 'withdrawal_fee_percent' => $withdrawalFeePercent, 'withdrawal_fee_fixed' => $withdrawalFeeFixed]);
    }

    public function updateOrCreateCoinPair($request, $edit_id=null)
    {
        if (isset($edit_id)) {
            $coinPair = CoinPair::where('id', decrypt($edit_id))->first();
            if (isset($coinPair)) {
                return $coinPair->update(['parent_coin_id' => $request->parent_coin_id, 'child_coin_id' => $request->child_coin_id]);
            }
            return false;
        } else {
            return CoinPair::create(['parent_coin_id' => $request->parent_coin_id, 'child_coin_id' => $request->child_coin_id, 'price' => $request->price, 'initial_price' => $request->price]);
        }
    }
}
