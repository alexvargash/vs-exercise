<?php

namespace Tests;

use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class RedisTestCase extends TestCase
{
    private $redisKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->redisKey = config('subscribers-queue.queue.key');
        Redis::del($this->redisKey);
    }

    protected function tearDown(): void
    {
        Redis::del($this->redisKey);
        parent::tearDown();
    }

    public function assertItemOnQueue($item)
    {
        $this->assertContains(json_encode($item), Redis::lrange($this->redisKey, 0, -1));
    }

    public function assertItemNotOnQueue($item)
    {
        $this->assertNotContains(json_encode($item), Redis::lrange($this->redisKey, 0, -1));
    }
}
