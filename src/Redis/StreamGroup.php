<?php

namespace FlowerAllure\ComposerUtils\App\Redis;

use Redis;
use FlowerAllure\ComposerUtils\App\Db\RedisConnect;

class StreamGroup
{
    private Redis $redis;

    public function __construct(public string $streamKey = '')
    {
        $this->redis = RedisConnect::getInstance();
    }

    public function getStreamKey(): string
    {
        return $this->streamKey;
    }

    public function setStreamKey(string $streamKey): void
    {
        $this->streamKey = $streamKey;
    }

    public function xAdd(string $id, array $message): static
    {
        $this->redis->xAdd($this->streamKey, $id, $message);

        return $this;
    }

    public function xGroupCreate(string $group, string $msgId): static
    {
        $this->redis->xGroup('create', $this->streamKey, $group, $msgId);

        return $this;
    }

    public function xReadGroupHead(string $group, string $consumer): array|Redis
    {
        return $this->redis->xReadGroup($group, $consumer, [$this->streamKey => '>'], 1);
    }

    public function xAck(): static
    {
        $hArr = $tArr = [];
        for ($i = 1; $i < 10; $i++) {
            $hArr[] = "0-$i";
            $hArr[] = "1-$i";
            $tArr[] = "1-$i";
        }
        $this->redis->xAck($this->streamKey, $this->headGroup, $hArr);
        $this->redis->xAck($this->streamKey, $this->tailGroup, $tArr);

        return $this;
    }

//    public function xReadGroupHead(): array|Redis
//    {
//        return $this->redis->xReadGroup($this->headGroup, 'c1', [$this->streamKey => '>'], 1);
//    }
//
//    public function xReadGroupTail(): array|Redis
//    {
//        return $this->redis->xReadGroup($this->tailGroup, 'c1', [$this->streamKey => '>'], 1);
//    }

    public function del(): static
    {
        $this->redis->del($this->streamKey);

        return $this;
    }
}
