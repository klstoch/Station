<?php

declare(strict_types=1);

namespace Station\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Station\Infrastructure\GeneratorID;

#[MappedSuperclass]
abstract class AbstractEntity
{
    #[Id]
    #[Column(type: 'string')]
    #[GeneratedValue(strategy: 'NONE')]
    protected ?string $id;

    #[Column(name: 'created_at', type: 'datetime')]
    private \DateTimeInterface $createdAt;

    public function __construct(?string $id) {
        $this->id = $id ?? GeneratorID::genID();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}
