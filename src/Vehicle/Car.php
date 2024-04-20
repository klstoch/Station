<?php

namespace Station\Vehicle;

readonly class Car
{
    public function __construct(
        private Wheel $wheel,
        private BodyEnum $body
    )
    {
    }

    /**
     * @return BodyEnum
     */
    public function getBody(): BodyEnum
    {
        return $this->body;
    }

    /**
     * @return Wheel
     */
    public function getWheel(): Wheel
    {
        return $this->wheel;
    }

    public function toArray(): array
    {
        return [
            'wheel' => [
                'disc_wheel' => [
                    'damage_enum' => $this->getWheel()->getDiscWheel()->getDamage()->value,
                    'disc_material_enum' => $this->getWheel()->getDiscWheel()->getDiscMaterial()->value,
                    'radius_enum' => $this->getWheel()->getDiscWheel()->getRadius()->value,
                ],
                'tyre' => [
                    'is_run_flat' => $this->getWheel()->getTyre()->isRun_flat(),
                    'radius_enum' => $this->getWheel()->getTyre()->getRadius()->value,
                ],
            ],
            'body_enum' => $this->getBody()->value,
        ];
    }
}