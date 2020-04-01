<?php

namespace App\Queues;

use Illuminate\Support\Facades\Redis;

class SubscribersQueue
{
    /**
     * Maximum quantity of subscribers allowed per batch.
     * @var int
     */
    private $batchQuantity;

    /**
     * Redis key for the subscribers queue.
     * @var string
     */
    private $redisKey;

    /**
     * Set the class attributes from the config file.
     */
    public function __construct()
    {
        $this->redisKey = config('subscribers-queue.queue.key');
        $this->batchQuantity = config('subscribers-queue.queue.batch_quantity');
    }

    /**
     * Search the given item, if it is found update the item otherwise push the
     * new item.
     *
     * @param array $userData
     * @return void
     */
    public function addOrUpdateItem($userData)
    {
        $search = $this->searchItem($userData['email']);

        $search !== false
            ? $this->updateItem($search, $userData)
            : $this->pushItem($userData);
    }

    /**
     * Search for an item based on the email, and return the position or false
     * if it is not on the queue.
     *
     * @param  string $userEmail
     * @return int | boolean
     */
    public function searchItem($userEmail)
    {
        return $this->getQueue()->pluck('email')->search($userEmail, true);
    }

    /**
     * Push a json encoded item to the end of the queue.
     *
     * @param  array $userData
     * @return void
     */
    public function pushItem($userData)
    {
        Redis::rpush($this->redisKey, json_encode($userData));
    }

    /**
     * Update an item in the queue by merging the data for the given index.
     *
     * @param  int $index
     * @param  array $userData
     * @return void
     */
    public function updateItem($index, $userData)
    {
        $oldData = $this->getQueue()->toArray()[$index];
        $mergeData = array_merge($oldData, $userData);

        Redis::lset($this->redisKey, $index, json_encode($mergeData));
    }

    /**
     * Get all the items in the queue as a collection.
     *
     * @return Illuminate\Support\Collection
     */
    public function getQueue()
    {
        return $this->queueToCollection(Redis::lrange($this->redisKey, 0, -1));
    }

    /**
     * A collection of items is returned and removed from the queue.
     *
     * @return Illuminate\Support\Collection
     */
    public function getBatch()
    {
        $batch = Redis::lrange($this->redisKey, 0, $this->batchQuantity - 1);
        Redis::ltrim($this->redisKey, $this->batchQuantity, -1);

        return $this->queueToCollection($batch);
    }

    /**
     * Get the queue items as a collection of arrays.
     *
     * @param  array $queue
     * @return Illuminate\Support\Collection
     */
    public function queueToCollection($queue)
    {
        return collect($queue)->map(function($item) {
            return json_decode($item, true);
        });
    }

    /**
     * Check if the queue is not empty.
     *
     * @return boolean
     */
    public function isNotEmpty()
    {
        return $this->getQueue()->isNotEmpty();
    }
}
