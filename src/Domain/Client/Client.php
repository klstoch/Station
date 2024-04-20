<?php

namespace Station\Domain\Client;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Station\Domain\AbstractEntity;
use Station\Domain\Client\Vehicle\Car;
use Station\Domain\Station\Station;
use Station\Domain\Work\AbstractWork;

#[Entity(repositoryClass: ClientRepositoryInterface::class)]
#[Table(name: 'clients')]
final class Client extends AbstractEntity
{
    private ?string $currentProblem = null;

    public function __construct(
        #[Column(name: 'name', type: 'string', length: 150)]
        private readonly string $name,

        #[Column(name: 'car', type: 'car')]
        private readonly Car $car,

        /** @param array<Station> $stations */
        #[ManyToMany(targetEntity: Station::class, inversedBy: 'clients')]
        #[JoinTable(name: 'stations_clients')]
        private array $stations = [],

        ?string $id = null,
    ) {
        parent::__construct($id);
    }

    public function agreeToWait(): bool
    {
        return (bool) random_int(0, 2);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function requestForTypeWork(): string
    {
        if ($this->currentProblem === null) {
            $works = AbstractWork::getAll();
            $this->currentProblem = $works[random_int(0, count($works) - 1)];
        }
        return $this->currentProblem;
    }

    public function assignToStation(Station $station): void
    {
        $this->stations[] = $station;
    }
}