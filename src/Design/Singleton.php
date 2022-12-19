<?php

namespace FlowerAllure\ComposerUtils\App\Design;

use LogicException;

class Singleton
{
    private static array $instances = [];

    protected function __clone()
    {
        throw new LogicException('Cannot clone singleton');
    }

    public function __wakeup()
    {
        throw new LogicException('Cannot serialize singleton');
    }

    public static function getInstance()
    {
        $subclass = static::class;
        if (!isset(self::$instances[$subclass])) {
            self::$instances[$subclass] = new static();
        }

        return self::$instances[$subclass];
    }
}
