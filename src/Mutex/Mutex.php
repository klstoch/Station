<?php


declare(strict_types = 1);


namespace Station\Mutex;

final readonly class Mutex
{
    public function __construct(
        private \Redis $redis,
        private string $owner,
    ) {
    }

    public function isLocked(string $process): bool
    {
        return $this->executeAndReplaceExceptions(fn() => $this->redis->exists($process));
    }

    /**
     * @throws AlreadyLockedException
     */
    public function lock(string $process): void
    {
        $isSet = $this->executeAndReplaceExceptions(fn() => $this->redis->setnx($process, $this->owner));
        if (!$isSet) {
            throw new AlreadyLockedException();
        }
    }

    public function waitAndLock(string $process, $attemptIntervalMs = 50): void
    {
        while (true) {
            try {
                $this->lock($process);
                return;
            } catch (AlreadyLockedException) {
                usleep($attemptIntervalMs * 1000);
            }
        }
    }

    public function unlock(string $process): void
    {
        $lockOwner = $this->executeAndReplaceExceptions(fn() => $this->redis->get($process));
        if ($lockOwner === $this->owner) {
            $this->executeAndReplaceExceptions(fn() => $this->redis->del($process));
        }
    }

    private function executeAndReplaceExceptions(callable $callback): mixed
    {
        try {
            return $callback();
        } catch (\RedisException $e) {
            throw new MutexRuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
