<?php

namespace App\Console\Commands;

use App\Model\Coin;
use App\Model\Wallet;
use Illuminate\Console\Command;

class addCoinType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:coinType';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $wallets = Wallet::where(['coin_type' => ''])->get();
            if (isset($wallets[0])) {
                foreach ($wallets as $wallet) {
                    $coin = Coin::find($wallet->coin_id);
                    if(empty($wallet->coin_type)) {
                        $wallet->update(['coin_type' => $coin->coin_type]);
                    }
                }
            }
        } catch (\Exception $e) {
            storeException('addCoinType',$e->getMessage());
        }

    }
}
