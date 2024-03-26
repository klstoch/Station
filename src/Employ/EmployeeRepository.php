<?php

namespace Station\Employ;


use Station\Infrastructure\Cache\Redis;
use Station\Infrastructure\GeneratorID;


class EmployeeRepository
{
    private string $uniqueKey;

    public function __construct(private readonly Redis $redis)
    {
        $this->uniqueKey = GeneratorID::genID();
    }

    public function getAll(): array
    {
        $employees = [];
        $serializedEmployees = $this->executeWithExceptionHandling(fn () => $this->redis->hGetAll('employees_'.$this->getUniqueKey()));
        foreach ($serializedEmployees as $serializedEmployee){
            $employees[] = unserialize($serializedEmployee, ['allowed_classes' => true]);
        }
        return $employees;
    }

    public function get(string $id): EmployInterface
    {
        $serializedEmployee = $this->executeWithExceptionHandling(fn() => $this->redis->hGet('employees_'.$this->getUniqueKey(), $id));
        if (!$serializedEmployee) {
            throw new \RuntimeException("Stations was not found by id: $id");
        }
        return unserialize($serializedEmployee,['allowed_classes' => true]);
    }


    public function saveEmployee(EmployInterface $employee):void
    {
        $this->executeWithExceptionHandling(fn () => $this->redis->hset('employees_'. $this->getUniqueKey(), $employee->getId(), serialize($employee)));
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