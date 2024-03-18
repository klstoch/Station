<?php

namespace Station\Queue;

use Station\Client;
use Station\Mutex\Mutex;

readonly class RedisBasedClientQueue implements ClientQueue
{

    private const PROCESS_CLIENT_QUEUE = 'RedisBasedClientQueue';

    public function __construct(
        private \Redis $redis,
        private Mutex  $mutex,

    )
    {

    }

    public function add(Client $client): void
    {

        $this->mutex->waitAndLock(self::PROCESS_CLIENT_QUEUE);
        echo 'начали';
        sleep(5);
        $clientQueue = $this->getClientQueue();
        $clientQueue[$client->getId()] = $client;
        $this->saveClientQueue($clientQueue);
        echo 'закончили';
        $this->mutex->unlock(self::PROCESS_CLIENT_QUEUE);
    }

    public function get(): Client|null
    {
        $this->mutex->waitAndLock(self::PROCESS_CLIENT_QUEUE);
        $clientQueue = $this->getClientQueue();
        $this->saveClientQueue($clientQueue);
        $this->mutex->unlock(self::PROCESS_CLIENT_QUEUE);
        return array_shift($clientQueue);
    }

    public function delete(Client $client): void
    {
        $this->mutex->waitAndLock(self::PROCESS_CLIENT_QUEUE);
        $clientQueue = $this->getClientQueue();
        unset($clientQueue[$client->getId()]);
        $this->saveClientQueue($clientQueue);
        $this->mutex->unlock(self::PROCESS_CLIENT_QUEUE);
    }

    public function isEmptyClientQueue(): bool
    {
        $clientQueue = $this->getClientQueue();
        return empty($clientQueue);
    }

    private function getClientQueue(): array
    {
        $serializedClientQueue = $this->redis->get('queue');
        return $serializedClientQueue ? unserialize($serializedClientQueue, ['allowed_classes' => true]) : [];
    }

    private function saveClientQueue(array $clientQueue): void
    {
        $serializedClientQueue = serialize($clientQueue);
        $this->redis->set('queue', $serializedClientQueue);
    }
}