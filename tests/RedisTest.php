<?php

/*
 * This file is part of the flower-allure/composer-utils.
 * (c) flower-allure <i@flower-allure.me>
 * This source file is subject to the LGPL license that is bundled.
 */

namespace FlowerAllure\ComposerUtils\Tests;

use Redis;
use PHPUnit\Framework\TestCase;

abstract class RedisTest extends TestCase
{
    private ?Redis $redis = null;

    /**
     * @throws \RedisException
     */
    protected function setUp(): void
    {
        $this->redis = (new Redis());
        $this->redis->connect('114.116.42.21');
        parent::setUp();
    }

    /**
     * @throws \RedisException
     */
    protected function tearDown(): void
    {
        $this->redis->close();
        parent::tearDown();
    }
}
