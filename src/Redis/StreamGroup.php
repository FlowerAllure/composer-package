<?php

/*
 * This file is part of the flower-allure/composer-utils.
 * (c) flower-allure <i@flower-allure.me>
 * This source file is subject to the LGPL license that is bundled.
 */

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

    public function xReadGroup(string $group, string $consumer): array|Redis
    {
        return $this->redis->xReadGroup($group, $consumer, [$this->streamKey => '>'], 1);
    }

    public function xAck(string $group, array $messages)
    {
        $this->redis->xAck($this->streamKey, $group, $messages);
    }

    public function del(): static
    {
        $this->redis->del($this->streamKey);

        return $this;
    }
}
