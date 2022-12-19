<?php

namespace FlowerAllure\ComposerUtils\Tests\Redis;

use FlowerAllure\ComposerUtils\Tests\RedisTest;
use FlowerAllure\ComposerUtils\App\Redis\StreamGroup;
use FlowerAllure\ComposerUtils\App\Redis\StreamQueue;

class StreamQueueTest extends RedisTest
{
    private StreamQueue $streamQueue;

    private ?StreamGroup $streamGroup = null;

    public function setUp(): void
    {
        $this->streamGroup = new StreamGroup();
        $this->streamGroup->setStreamKey('my_stream');
        $this->streamQueue = new StreamQueue($this->streamGroup);
        parent::setUp();
    }

    public function testWork()
    {
        $this->streamQueue->work();
        $this->assertTrue(true);
    }
}
