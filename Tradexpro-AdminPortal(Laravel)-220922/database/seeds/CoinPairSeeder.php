<?php

use App\Http\Repositories\AdminCoinRepository;
use App\Model\Coin;
use App\Model\CoinPair;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CoinPairSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coins = Coin::all();

        foreach ($coins as $parentCoin) {
            if ($parentCoin->is_base && $parentCoin->status && $parentCoin->trade_status) {
                foreach ($coins as $childCoin) {
//                    if (($childCoin->id != $parentCoin->id) && (!$childCoin->is_currency)  && $parentCoin->status && $parentCoin->trade_status)
//                    CoinPair::firstOrCreate(['parent_coin_id' => $parentCoin->id, 'child_coin_id' => $childCoin->id],
//                        [
//                            'created_at' => Carbon::now(),
//                            'updated_at' => Carbon::now()
//                        ]);
                }
            }
        }
    }
}
