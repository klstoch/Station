<?php

namespace Station\Employ;

interface GraphWork
{
   public function isWorkTime(\DateTimeInterface $dateTime): bool;
}