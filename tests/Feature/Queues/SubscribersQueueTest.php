<?php

namespace Tests\Feature\Queues;

use App\Queues\SubscribersQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\RedisTestCase;

class SubscribersQueueTest extends RedisTestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_item_can_be_pushed_to_the_queue()
    {
        $subscribersQueue = new SubscribersQueue();

        $item = [
            'id' => 1,
            'email' => 'test@example.com',
            'first_name' => 'Alex',
            'timezone' => 'CST'
        ];

        $subscribersQueue->pushItem($item);

        $this->assertItemOnQueue($item);
    }

    /** @test */
    public function an_item_can_be_updated_on_the_queue()
    {
        $subscribersQueue = new SubscribersQueue();

        $item = [
            'id' => 1,
            'email' => 'test@example.com',
            'first_name' => 'Alex',
            'timezone' => 'CST'
        ];

        $subscribersQueue->pushItem($item);

        $updatedItem = [
            'id' => 1,
            'email' => 'test@example.com',
            'first_name' => 'John',
            'timezone' => 'CET'
        ];

        $subscribersQueue->updateItem(0, $updatedItem);

        $this->assertItemOnQueue($updatedItem);
    }

    /** @test */
    public function an_item_can_be_searched_by_email_on_the_queue()
    {
        $subscribersQueue = new SubscribersQueue();

        $item = [
            'id' => 1,
            'email' => 'test@example.com',
            'first_name' => 'Alex',
            'timezone' => 'CST'
        ];

        $subscribersQueue->pushItem($item);
        $search = $subscribersQueue->searchItem($item['email']);

        $this->assertEquals(0, $search);
    }

    /** @test */
    public function an_item_is_added_if_it_is_not_on_the_queue()
    {
        $subscribersQueue = new SubscribersQueue();

        $item = [
            'id' => 1,
            'email' => 'test@example.com',
            'first_name' => 'Alex',
            'timezone' => 'CST'
        ];

        $subscribersQueue->addOrUpdateItem($item);

        $this->assertItemOnQueue($item);
    }

    /** @test */
    public function an_item_is_updated_with_merge_data_if_it_is_already_on_the_queue()
    {
        $subscribersQueue = new SubscribersQueue();

        $item = [
            'id' => 1,
            'email' => 'test@example.com',
            'first_name' => 'Alex',
            'timezone' => 'CST'
        ];

        $subscribersQueue->addOrUpdateItem($item);

        $updatedItem = [
            'id' => 1,
            'email' => 'test@example.com',
            'first_name' => 'John',
        ];

        $subscribersQueue->addOrUpdateItem($updatedItem);

        $mergeData = array_merge($item, $updatedItem);

        $this->assertItemOnQueue($mergeData);
    }

    /** @test */
    public function all_the_imtems_on_the_queue_can_be_retrieved()
    {
        $subscribersQueue = new SubscribersQueue();

        $item = [
            'id' => 1,
            'email' => 'test@example.com',
            'first_name' => 'Alex',
            'timezone' => 'CST'
        ];

        $item2 = [
            'id' => 1,
            'email' => 'awesome@example.com',
            'first_name' => 'John',
            'timezone' => 'CET'
        ];

        $subscribersQueue->addOrUpdateItem($item);
        $subscribersQueue->addOrUpdateItem($item2);

        $this->assertCount(2, $subscribersQueue->getQueue());
    }

    /** @test */
    public function the_queue_is_retrieved_as_a_collection()
    {
        $subscribersQueue = new SubscribersQueue();

        $item = [
            'id' => 1,
            'email' => 'test@example.com',
            'first_name' => 'Alex',
            'timezone' => 'CST'
        ];

        $subscribersQueue->addOrUpdateItem($item);

        $this->assertInstanceOf(Collection::class, $subscribersQueue->getQueue());
    }

}
