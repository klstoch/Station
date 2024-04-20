<?php

namespace Station\Employ\Graph;

interface GraphWork
{
   public function isWorkTime(\DateTimeInterface $dateTime): bool;

    public function toArray(): array;
}