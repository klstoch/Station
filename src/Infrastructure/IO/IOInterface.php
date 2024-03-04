<?php

namespace Station\Infrastructure\IO;
interface IOInterface
{
    public function requestInput(
        string  $message,
        array   $answers = [],
        ?string $default = null,
        string  $errorMessage = 'Неверный ввод ',
    );
}