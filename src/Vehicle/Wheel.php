<?php

namespace Station\Vehicle;

readonly class Wheel
{
    public function __construct(
        private  DiscWheel  $discWheel,
        private  Tyre $tyre,

    ) {
         if ($this->getTyre()->getRadius() !== $this->getDiscWheel()->getRadius())
         {
             throw new \RuntimeException('Радиус колесного диска не соответсвует радиусу шины');
         }
    }


    /**
     * @return DiscWheel
     */
    public function getDiscWheel(): DiscWheel
    {
        return $this->discWheel;
    }

    /**
     * @return Tyre
     */
    public function getTyre(): Tyre
    {
        return $this->tyre;
    }
}