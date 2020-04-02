<?php

namespace Tests;

trait EventTestTrait
{
    /**
     * Asset that the provided event was fired the provided count.
     *
     * @param  int $count
     * @param  string $event
     * @return void
     */
    public function assertEventFiredTimes($count, $event)
    {
        $events = collect($this->firedEvents)->groupBy(function($item, $key) {
            return get_class($item);
        })->get($event);

        $this->assertEquals($count, count($events));
    }
}
