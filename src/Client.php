<?php

namespace Station;

use Station\Vehicle\Car;
use Station\Work\WorkEnum;

readonly class Client
{
    private string $id;

    public function __construct(
        private string $name,
        private Car    $car,
    )
    {
        $this->id = GeneratorID::genID();
    }

    public function agreeToWait(): bool
    {
        return (bool)random_int(0, 2);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Car
     */
    public function getCar(): Car
    {
        return $this->car;
    }

    public function createWorkName(): WorkEnum
    {
        $works = WorkEnum::cases();
        return $works[random_int(0, count($works) - 1)];

    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

}
/*$workName = $works[random_int(0, count($works) - 1)];

$work = match ($workName) {
    WorkEnum::tireReplacement => new TireReplacement($time, $logger,),
    WorkEnum::wheelBalancing => new WheelBalancing($time, $logger),
    WorkEnum::wheelReplacementBalancing => new WheelReplacementBalancing($time, $logger),
    default => new TireReplacement($time, $logger),
};*/