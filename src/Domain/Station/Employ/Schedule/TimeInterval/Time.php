<?php

namespace Station\Domain\Station\Employ\Schedule\TimeInterval;

final readonly class Time
{
    public string $value;
    public int $hours;
    public int $minutes;

    public function __construct(string $value) {
        [$hours, $minutes] = explode(':', trim($value));
        if ($hours < '00' || $hours > '23' || $minutes < '00' || $minutes > '59') {
            throw new \InvalidArgumentException('Invalid time');
        }

        $this->value = $value;
        $this->hours = (int) $hours;
        $this->minutes = (int) $minutes;
    }

    public static function from(string $value): self // принадлежит классу и не имеет доступа к объектам
    {
        return new self($value);
    }
}