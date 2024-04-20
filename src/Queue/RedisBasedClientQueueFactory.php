<?php

namespace Station\Queue;

use Station\Infrastructure\Cache\Redis;
use Station\Mutex\Mutex;
use Station\PilotStation\Station;

class RedisBasedClientQueueFactory implements ClientQueueFactoryInterface
{
    public function __construct(
        private readonly Redis $redis,
    ) {
    }

    public function create(Station $station): ClientQueue
    {
        $mutex = new Mutex(redis: $this->redis, owner: 'RedisBasedClientQueue');
        return new RedisBasedClientQueue($this->redis, $mutex);
    }
}