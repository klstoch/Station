<?php

namespace Station\Infrastructure\IO;

use Symfony\Component\Console\Completion\CompletionInput;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\ConsoleOutput;

class IOFactory
{
    public function create(): IOInterface
    {
        $input = new CompletionInput();
        $output = new ConsoleOutput();
        $helper = new QuestionHelper();
        return new SymphonyIO($input, $output, $helper);
    }
}