<?php

namespace Tests\Feature\Observers;

use App\Queues\SubscribersQueue;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\RedisTestCase;

class UserObserversTest extends RedisTestCase
{
    use RefreshDatabase;

    /** @test */
    public function when_a_user_is_updated_it_should_be_pushed_to_the_queue()
    {
        $subscribersQueue = new SubscribersQueue();
        $user = factory(User::class)->create();

        $user->update([
            'firstname' => 'Random',
            'timezone' => 'America/Los_Angeles',
        ]);

        $this->assertCount(1, $subscribersQueue->getQueue());
    }

    /** @test */
    public function only_the_updated_attributes_email_and_id_should_be_pushed_to_queue()
    {
        $subscribersQueue = new SubscribersQueue();
        $user = factory(User::class)->create();

        $user->update([
            'timezone' => 'America/Los_Angeles',
        ]);

        $this->assertCount(1, $subscribersQueue->getQueue());
        $this->assertArrayNotHasKey('firstname', $subscribersQueue->getQueue()->first());
        $this->assertArrayHasKey('timezone', $subscribersQueue->getQueue()->first());
        $this->assertArrayHasKey('email', $subscribersQueue->getQueue()->first());
        $this->assertArrayHasKey('id', $subscribersQueue->getQueue()->first());
    }

    /** @test */
    public function if_a_user_is_already_in_the_queue_and_is_updated_again_the_new_updates_should_be_merged()
    {
        $subscribersQueue = new SubscribersQueue();
        $user = factory(User::class)->create();

        $user->update([
            'firstname' => 'Random',
        ]);

        $this->assertCount(1, $subscribersQueue->getQueue());
        $this->assertArrayHasKey('firstname', $subscribersQueue->getQueue()->first());
        $this->assertArrayNotHasKey('timezone', $subscribersQueue->getQueue()->first());
        $this->assertEquals('Random', $subscribersQueue->getQueue()->first()['firstname']);

        $user->fresh();
        $user->update([
            'timezone' => 'America/Los_Angeles',
        ]);

        $this->assertCount(1, $subscribersQueue->getQueue());
        $this->assertArrayHasKey('firstname', $subscribersQueue->getQueue()->first());
        $this->assertArrayHasKey('timezone', $subscribersQueue->getQueue()->first());
        $this->assertEquals('Random', $subscribersQueue->getQueue()->first()['firstname']);
        $this->assertEquals('America/Los_Angeles', $subscribersQueue->getQueue()->first()['timezone']);
    }
}
