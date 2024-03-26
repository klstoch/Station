<?php

namespace Station\Client;

use Station\Infrastructure\GeneratorID;
use Station\Vehicle\Car;
use Station\Work\AbstractWork;

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
    public function requestForTypeWork(): string
        {
            $works = AbstractWork::getAll();
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