<?php
namespace Station\Employ;

enum Grade: string
{
    case intern = 'стажер';
    case starting = 'начинающий';
    case medium = 'средний';
    case expert = 'опытный';

}