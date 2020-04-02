<?php

namespace App\Console\Commands;

use App\Queues\SubscribersQueue;
use App\Services\ServiceSubscribersUpdate;
use Illuminate\Console\Command;

class CallServiceSubscribersUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'call:service {service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call a service class.';

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
    public function handle(SubscribersQueue $subscribersQueue)
    {
        if ($this->argument('service') == 'subscribers-update') {
            $service = app(ServiceSubscribersUpdate::class);
            $service($subscribersQueue);

            $this->info('The service subscribers update was called.');
        }
    }
}
