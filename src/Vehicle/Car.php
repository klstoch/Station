<?php

namespace Station\Vehicle;

readonly class Car
{
    public function __construct(
        private  Wheel    $wheel,
        private  BodyEnum $body
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
}