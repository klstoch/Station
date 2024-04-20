<?php

namespace Station\Domain\Station\Employ\Schedule;

interface ScheduleInterface
{
   public function isWorkTime(\DateTimeInterface $dateTime): bool;

    public function toArray(): array;

    public static function fromArray(array $data): static;
}