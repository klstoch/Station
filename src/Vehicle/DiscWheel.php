<?php

namespace Station\Vehicle;

readonly class DiscWheel
{
    public function __construct(
        private DamageEnum $damage,
        private DiscMaterialEnum $discMaterial,
        private RadiusEnum $radius
    ) {
        
    }

    /**
     * @return DamageEnum
     */
    public function getDamage(): DamageEnum
    {
        return $this->damage;
    }

    /**
     * @return RadiusEnum
     */
    public function getRadius(): RadiusEnum
    {
        return $this->radius;
    }

    /**
     * @return DiscMaterialEnum
     */
    public function getDiscMaterial(): DiscMaterialEnum
    {
        return $this->discMaterial;
    }

}