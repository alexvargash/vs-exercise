<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessSubscribersBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Subscribers batch.
     * @var Collection
     */
    protected $subscribersBatch;

    /**
     * Set the subscribers batch.
     *
     * @param  Collection  $subscribersBatch
     * @return void
     */
    public function __construct($subscribersBatch)
    {
        $this->subscribersBatch = $subscribersBatch;
    }

    /**
     * Log the updated attributes and the id for each item of the subscribers
     * batch.
     *
     * @return void
     */
    public function handle()
    {
        $this->subscribersBatch->each(function($item) {
            Log::info($this->formatInfoMessage($item));
        });
    }

    /**
     * Format the info message with the id at the beginning and the the updated
     * fields with the key and the value.
     *
     * @param  array $item
     * @return string
     */
    public function formatInfoMessage($item)
    {
        $infoMessage = "[{$item['id']}]";

        $infoMessage .= collect($item)->only(['firstname', 'timezone'])->reduce(function ($message, $value) use ($item) {
            return $message .= ' ' . array_keys($item, $value)[0] . ': ' . $value . ',';
        }, '');

        return Str::replaceLast(',', '', $infoMessage);
    }
}
