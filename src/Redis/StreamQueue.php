<?php

/*
 * This file is part of the flower-allure/composer-utils.
 * (c) flower-allure <i@flower-allure.me>
 * This source file is subject to the LGPL license that is bundled.
 */

namespace FlowerAllure\ComposerUtils\App\Redis;

class StreamQueue
{
    private ?StreamGroup $streamGroup = null;

    public function __construct(StreamGroup $streamGroup)
    {
        $this->streamGroup = $streamGroup;
    }

    public function work($group)
    {
        while (true) {
            return $this->streamGroup->xReadGroup($group, 'test');
        }
    }
}
