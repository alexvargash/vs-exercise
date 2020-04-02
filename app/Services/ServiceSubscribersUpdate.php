<?php

namespace App\Services;

use App\Jobs\ProcessSubscribersBatch;
use App\Queues\SubscribersQueue;

class ServiceSubscribersUpdate
{
    /**
     * Batches that are available.
     * @var int
     */
    private $batchesAvailable;

    /**
     * Set the batch quantity.
     */
    public function __construct()
    {
        $this->batchesAvailable = config('subscribers-queue.queue.batch_quantity');
    }

    /**
     * Review if batches can be used and the queue is not empty, and dispatch a
     * job with a batch.
     *
     * @param  SubscribersQueue $subscribersQueue
     * @return void
     */
    public function __invoke(SubscribersQueue $subscribersQueue)
    {
        while ($this->batchesAvailable > 0 && $subscribersQueue->isNotEmpty()) {
            ProcessSubscribersBatch::dispatch($subscribersQueue->getBatch());
            $this->batchesAvailable--;
        }
    }
}
