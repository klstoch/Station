<?php

namespace Station\Domain\Time;

readonly class VirtualTime
{
    public function __construct(
        private float              $startRealTime, // реальное время создания объекта
        private \DateTimeImmutable $startVirtualTime, // начало отсчета времени деятельности
        private float              $scale = 60 // "масштаб"
    ) {
    }

    public function current(): \DateTimeInterface
    {
        $currentTime = microtime(true); // реальное время с 1970
        $realDuration = round($currentTime - $this->startRealTime, 2); // продолжительность в реальном времени
        $virtualDuration = round($realDuration * $this->scale);// виртуальная продолжительность ( произведение реальной продолжительности на масштаб)
        return $this->startVirtualTime->modify("+$virtualDuration seconds");
    }

    public function wait(Duration $duration): void
    {
        $realSeconds = $duration->asSeconds() / $this->scale;
        $realMicroSeconds = (int)($realSeconds * 1000000);
        usleep($realMicroSeconds);
    }

    public function toArray():array
    {
        return[
            'startRealTime'=> $this->startRealTime,
            'startVirtualTime'=> $this->startVirtualTime->format('Y-m-d'),
            'scale'=> $this->scale
        ];
    }
}