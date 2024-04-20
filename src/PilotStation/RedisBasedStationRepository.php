<?php

namespace Station\PilotStation;

use Station\Infrastructure\Cache\Redis;
use Station\Mutex\Mutex;

class RedisBasedStationRepository
{
    private const MUTEX_KEY = 'StationRepository';

    public function __construct(
        private readonly Redis $redis,
        private readonly Mutex $mutex,
    ) {
    }

    /**
     * @return array<Station>
     */
    public function getAll(): array
    {
        $stations = [];

        $this->mutex->waitAndLock(self::MUTEX_KEY);
        $serializedStations = $this->executeWithExceptionHandling(fn() => $this->redis->hGetAll('stations') ?: []);
        $this->mutex->unlock(self::MUTEX_KEY);

        foreach ($serializedStations as $serializedStation) {
            $stations[] = unserialize($serializedStation, ['allowed_classes' => true]);
        }

        return $stations;
    }

    public function get(string $id): Station
    {
        $serializedStation = $this->executeWithExceptionHandling(fn () => $this->redis->hGet('stations', $id));
        if (!$serializedStation) {
            throw new \RuntimeException("Stations was not found by id: $id");
        }
        return unserialize($serializedStation, ['allowed_classes' => true]);
    }

    public function save(Station $station): void
    {
        $this->mutex->waitAndLock(self::MUTEX_KEY);
        $this->executeWithExceptionHandling(fn () => $this->redis->hSet('stations', $station->getId(), serialize($station)));
        $this->mutex->unlock(self::MUTEX_KEY);
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