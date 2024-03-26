<?php

namespace Station\Infrastructure\Cache;

class Redis extends \Redis
{
    private const DEFAULT_HOST = '127.0.0.1';
    public function __construct()
    {
        parent::__construct();

        $this->connect(self::DEFAULT_HOST);
    }

    /**
     * @throws \RedisException
     */
    public function __wakeup(): void
    {
        $this->connect(self::DEFAULT_HOST);
    }
}