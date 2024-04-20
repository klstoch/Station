<?php

namespace Station\Infrastructure\Cache\Redis;

use Station\Domain\Client\Client;
use Station\Infrastructure\Cache\Redis\Redis;
use Station\Infrastructure\GeneratorID;

class ClientBaseRepository
{

    private string $uniqueKey;

    public function __construct(private readonly Redis $redis)
    {
        $this->uniqueKey = GeneratorID::genID();
    }

    public function getAll(): array
    {
        $results = [];
        $serializedClients = $this->executeWithExceptionHandling(fn() => $this->redis->hGetAll('clientBase_' . $this->getUniqueKey()));
        foreach ( $serializedClients as $serializedClient){
            $results[] = unserialize($serializedClient, ['allowed_classes' => true]);
        }
        return $results;
    }

    public function save(Client $client):void
    {
       $this->executeWithExceptionHandling(fn() => $this->redis->hSet('clientBase_'.$this->getUniqueKey(), $client->getId(), serialize($client)));
    }

    /**
     * @return string
     */
    public function getUniqueKey(): string
    {
        return $this->uniqueKey;
    }
    private function executeWithExceptionHandling(callable $callable): mixed
    {
        try {
            return $callable();
        } catch (\RedisException $e) {
            throw new \RuntimeException('Can`t execute redis operation', previous: $e);
        }
    }
}