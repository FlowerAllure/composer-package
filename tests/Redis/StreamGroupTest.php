<?php
namespace FlowerAllure\ComposerUtils\Tests\Redis;

use FlowerAllure\ComposerUtils\Tests\RedisTest;
use FlowerAllure\ComposerUtils\App\Redis\StreamGroup;

class StreamGroupTest extends RedisTest
{
    private ?StreamGroup $streamGroup = null;

    protected function setUp(): void
    {
        $this->streamGroup = new StreamGroup();
        $this->streamGroup->setStreamKey('my_stream');
        parent::setUp();
    }

    public function testXGroupTrain()
    {
        $this->streamGroup->del();
        for ($i = 1; $i < 10; $i++) {
            $this->streamGroup->xAdd("0-$i", ['value' => "$i"]);
        }
        $this->streamGroup->xGroupCreate('head_group', 0);
        for ($i = 1; $i < 10; $i++) {
            $this->streamGroup->xAdd("1-$i", ['value' => "1$i"]);
        }
        $this->streamGroup->xGroupCreate('tail_group', '$');
        $this->assertTrue(true);
    }

    public function testXReadHeadGroup()
    {
        while ($xReadGroupResult = $this->streamGroup->xReadGroupHead()) {
            var_dump($xReadGroupResult);
        }
        $this->assertTrue(true);
    }

    public function testXReadTailGroup()
    {
        while ($xReadGroupResult = $this->streamGroup->xReadGroupTail()) {
            var_dump($xReadGroupResult);
        }
        $this->assertTrue(true);
    }

    public function testXGroupAck()
    {
        $this->streamGroup->xAck();
        $this->assertTrue(true);
    }
}
