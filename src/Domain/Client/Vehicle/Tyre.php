<?php

namespace Station\Domain\Client\Vehicle;

readonly class Tyre
{
    public function __construct(
        private bool       $isRun_flat,
        private RadiusEnum $radius
    ) {

    }

    /**
     * @return bool
     */
    public function isRun_flat(): bool
    {
        return $this->isRun_flat;
    }

    /**
     * @return RadiusEnum
     */
    public function getRadius(): RadiusEnum
    {
        return $this->radius;
    }
}