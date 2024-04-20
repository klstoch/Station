<?php

declare(strict_types=1);

namespace Station\Domain\Time;

final readonly class Duration
{
    private int $seconds;

    public function __construct(int $seconds = 0, int $minutes = 0, int $hours = 0, int $days = 0) {
        $this->seconds = $seconds + 60 * $minutes + 60 * 60 * $hours + 60 * 60 * 24 * $days;
    }

    public static function s(int $second): self
    {
        return new self(seconds: $second);
    }

    public static function m(int $minutes): self
    {
        return new self(minutes: $minutes);
    }

    public static function h(int $hours): self
    {
        return new self(hours: $hours);
    }

    public static function d(int $days): self
    {
        return new self(days: $days);
    }

    public function asSeconds(): int
    {
        return $this->seconds;
    }
}
