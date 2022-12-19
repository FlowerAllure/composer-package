<?php

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
            $xReadGroupResult = $this->streamGroup->xReadGroupHead();

            break ;
        }
    }
}
