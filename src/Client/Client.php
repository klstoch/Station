<?php

namespace Station\Client;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Station\Infrastructure\GeneratorID;
use Station\PilotStation\Station;
use Station\Vehicle\Car;
use Station\Work\AbstractWork;

#[Entity]
#[Table(name: 'clients')]
readonly class Client
{
    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(name: 'id', type: 'integer', nullable: false)]
    private string $id;

    public function __construct(
        #[Column(name: 'name', type: 'string', length: 150, nullable: false)]
        private string $name,
        #[Column(name: 'car', type: 'car')]
        private Car $car,
        #[ManyToOne(targetEntity: Station::class, inversedBy: 'clients')]
        private Station $station,
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