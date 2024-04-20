<?php

namespace Station\Infrastructure\Cache\Redis\ClientQueue;

use Station\Domain\Client\Client;
use Station\Domain\Station\ClientQueue\ClientQueueInterface;
use Station\Infrastructure\Cache\Redis\Redis;
use Station\Infrastructure\GeneratorID;
use Station\Infrastructure\Mutex\Mutex;

readonly class ClientQueue implements ClientQueueInterface
{
    private const PROCESS_CLIENT_QUEUE = 'RedisBasedClientQueue';
    private string $uniqueKey;

    public function __construct(
        private Redis $redis,
        private Mutex $mutex,
    ) {
        $this->uniqueKey = GeneratorID::genID();
    }

    public function add(Client $client): void
    {
        $this->mutex->waitAndLock(self::PROCESS_CLIENT_QUEUE);
        $clientQueue = $this->getClientQueue();
        $clientQueue[$client->getId()] = $client;
        $this->saveClientQueue($clientQueue);
        $this->mutex->unlock(self::PROCESS_CLIENT_QUEUE);
    }

    public function get(): Client|null
    {
        $this->mutex->waitAndLock(self::PROCESS_CLIENT_QUEUE);
        $clientQueue = $this->getClientQueue();
        $client = array_shift($clientQueue);
        $this->saveClientQueue($clientQueue);
        $this->mutex->unlock(self::PROCESS_CLIENT_QUEUE);
        return $client;
    }

    public function delete(Client $client): void
    {
        $this->mutex->waitAndLock(self::PROCESS_CLIENT_QUEUE);
        $clientQueue = $this->getClientQueue();
        unset($clientQueue[$client->getId()]);
        $this->saveClientQueue($clientQueue);
        $this->mutex->unlock(self::PROCESS_CLIENT_QUEUE);
    }

    public function isEmpty(): bool
    {
        $clientQueue = $this->getClientQueue();
        return empty($clientQueue);
    }

    public function count(): int
    {
        return count($this->getClientQueue());
    }

    private function getClientQueue(): array
    {
        $serializedClientQueue = $this->executeWithExceptionHandling(fn () => $this->redis->get('queue_' . $this->getUniqueKey()));
        return $serializedClientQueue ? unserialize($serializedClientQueue, ['allowed_classes' => true]) : [];
    }

    private function saveClientQueue(array $clientQueue): void
    {
      $this->executeWithExceptionHandling(fn() => $this->redis->set('queue_' . $this->getUniqueKey(), serialize($clientQueue)));
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