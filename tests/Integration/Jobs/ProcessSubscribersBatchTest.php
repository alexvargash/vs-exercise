<?php

namespace Tests\Integration\Jobs;

use App\Jobs\ProcessSubscribersBatch;
use App\Queues\SubscribersQueue;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\RedisTestCase;

class ProcessSubscribersBatchTest extends RedisTestCase
{
    use RefreshDatabase;

    /**
     * The batch value is 5 for testing, override in phpunit.xml
     * @test
     */
    public function when_the_job_id_processed_the_batch_data_should_be_logged()
    {
        $subscribersQueue = new SubscribersQueue();
        Log::shouldReceive('info')->times(5);
        factory(User::class, 20)->create();

        $this->artisan('update:users');

        ProcessSubscribersBatch::dispatchNow($subscribersQueue->getBatch());
    }

    /**
     * The batch value is 5 for testing, override in phpunit.xml
     * @test
     */
    public function when_the_job_id_processed_the_batch_data_should_removed_from_the_queue()
    {
        $subscribersQueue = new SubscribersQueue();
        Log::shouldReceive('info')->times(5);
        factory(User::class, 20)->create();

        $this->artisan('update:users');

        $this->assertCount(20, $subscribersQueue->getQueue());

        ProcessSubscribersBatch::dispatchNow($subscribersQueue->getBatch());

        $this->assertCount(15, $subscribersQueue->getQueue());
    }

}
