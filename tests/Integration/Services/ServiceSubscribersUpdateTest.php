<?php

namespace Tests\Integration\Services;

use App\Queues\SubscribersQueue;
use App\Services\ServiceSubscribersUpdate;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\EventTestTrait;
use Tests\RedisTestCase;

class ServiceSubscribersUpdateTest extends RedisTestCase
{
    use RefreshDatabase;
    use EventTestTrait;

    /**
     * The batch value is 5 and the batch quantity is 3 for testing overrides
     * in phpunit.xml
     * @test
     */
    public function hello_world()
    {
        $this->withoutEvents();
        Log::shouldReceive('info')->times(15);

        $subscribersQueue = new SubscribersQueue();
        factory(User::class, 20)->create();
        $this->artisan('update:users');

        (new ServiceSubscribersUpdate)($subscribersQueue);

        $this->assertEventFiredTimes(3, 'Illuminate\Queue\Events\JobProcessing');
        $this->assertEventFiredTimes(3, 'Illuminate\Queue\Events\JobProcessed');
    }
}
