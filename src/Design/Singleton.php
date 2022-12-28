<?php

/*
 * This file is part of the flower-allure/composer-utils.
 * (c) flower-allure <i@flower-allure.me>
 * This source file is subject to the LGPL license that is bundled.
 */

namespace FlowerAllure\ComposerUtils\App\Design;

class Singleton
{
    private static array $instances = [];

    protected function __clone()
    {
        throw new \LogicException('Cannot clone singleton');
    }

    public function __wakeup()
    {
        throw new \LogicException('Cannot serialize singleton');
    }

    public static function getInstance()
    {
        $subclass = static::class;
        if (! isset(self::$instances[$subclass])) {
            self::$instances[$subclass] = new static();
        }

        return self::$instances[$subclass];
    }
}
