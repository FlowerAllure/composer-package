<?php

namespace FlowerAllure\ComposerUtils\App\Db;

use Redis;
use FlowerAllure\ComposerUtils\App\Design\Singleton;

class RedisConnect extends Singleton
{
    private Redis $redis;

    protected function __construct()
    {
        try {
            $this->redis = new Redis();
            $this->redis->connect('114.116.42.21');
        } catch (\RedisException $exception) {
            var_dump($exception);
            exit;
        }
    }

    public static function getInstance()
    {
        $instance = parent::getInstance();

        return $instance->getRedis();
    }

    public function getRedis(): Redis
    {
        return $this->redis;
    }
}
