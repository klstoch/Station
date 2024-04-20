<?php

namespace Station\Time;

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

    public function wait(int $seconds = 0, int $minute = 0, int $hour = 0, int $day = 0): void
    {
        $allVirtualSeconds = $seconds + 60 * $minute + 60 * 60 * $hour + 60 * 60 * 24 * $day;
        $realSeconds = $allVirtualSeconds / $this->scale;
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