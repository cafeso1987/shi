<?php

namespace App\Console\Commands;

use App\Http\Repositories\CustomTokenRepository;
use Illuminate\Console\Command;

class TokenDepositCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:erc20token-deposit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Erc20 token deposit command';

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
        storeException('custom erc20 token deposit command', 'Called from tradexpro');
        $repo = new CustomTokenRepository();
        $repo->depositCustomERC20Token();
    }
}
