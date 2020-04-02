<?php

namespace App\Observers;

use App\Queues\SubscribersQueue;
use App\User;

class UserObserver
{
    /**
     * Item to add to the queue.
     * @var array
     */
    private $item;

    /**
     * Subscribers queue.
     * @var SubscribersQueue
     */
    private $subscribersQueue;

    /**
     * Create an empty item and set the subscribers queue.
     *
     * @param SubscribersQueue $subscribersQueue
     */
    public function __construct(SubscribersQueue $subscribersQueue)
    {
        $this->item = [];
        $this->subscribersQueue = $subscribersQueue;
    }

    /**
     * Add the updated user's attributes, id and email to the subscribers queue.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        $item['id'] = $user->id;
        $item['email'] = $user->email;

        collect($user->only(['firstname', 'timezone']))->each(function($value, $key) use (&$item, $user) {
            if ($user->isDirty($key)) {
                $item[$key] = $value;
            }
        });

        $this->subscribersQueue->addOrUpdateItem($item);
    }
}
