<?php

namespace Station\Infrastructure\IO;


use Symfony\Component\Console\Completion\CompletionInput;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Question\ChoiceQuestion;

readonly class SymphonyIO implements IOInterface
{
    public function __construct(
        private CompletionInput $input,
        private ConsoleOutput   $output,
        private QuestionHelper  $helper,
    ) {
    }

    public function requestInput(
        string  $message,
        array   $answers = [],
        ?string $default = null,
        string  $errorMessage = 'Неверный ввод '
    ) : string
    {
       $question = new ChoiceQuestion($message, $answers, $default);
       $question->setErrorMessage($errorMessage);
       return $this->helper->ask($this->input, $this->output, $question);
    }
}
