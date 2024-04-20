<?php

namespace Station\Infrastructure\Cache\Redis\ClientQueue;

use Station\Domain\Station\ClientQueue\ClientQueueFactoryInterface;
use Station\Infrastructure\Cache\Redis\ClientQueue\ClientQueue;
use Station\Infrastructure\Cache\Redis\Redis;
use Station\Infrastructure\Mutex\Mutex;
use Station\Domain\Station\Station;

readonly class RedisBasedClientQueueFactory implements ClientQueueFactoryInterface
{
    public function __construct(
        private Redis $redis,
    ) {
    }

    public function create(Station $station): ClientQueue
    {
        $mutex = new Mutex(redis: $this->redis, owner: 'RedisBasedClientQueue');
        return new ClientQueue($this->redis, $mutex);
    }
}