<?php

namespace Station\Infrastructure;

use Ramsey\Uuid\Uuid;

class GeneratorID
{
    public static function  genID(): string
    {
        $uuid = Uuid::uuid4();
        return $uuid->tostring();
    }
}