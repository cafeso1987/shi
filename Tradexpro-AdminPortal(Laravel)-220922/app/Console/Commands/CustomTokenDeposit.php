<?php

namespace App\Console\Commands;

use App\Http\Repositories\CustomTokenRepository;
use Illuminate\Console\Command;

class CustomTokenDeposit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom-token-deposit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor the blockchain deposit and if find new deposit then send this token to admin address';

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
     * @return mixed
     */
    public function handle()
    {
        storeException('custom token deposit command', 'Called from tradexpro');
        $repo = new CustomTokenRepository();
        $repo->depositCustomToken();
    }
}
