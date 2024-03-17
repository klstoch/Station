<?php

namespace Station\Employ;


use Station\Company_Station\GeneratorID;
use Station\Inventory\Inventory;
use Station\Logger\LoggerInterface;
use Station\Time\VirtualTime;
use Station\Tool\ToolEnum;
use Station\Tool\ToolInterface;
use Station\ToolNotFoundException;
use Station\Work\WorkEnumAdditional;
use Station\Work\WorkEnumRequired;
use Station\Work\WorkInterface;

abstract class AbstractEmploy implements EmployInterface
{
    /** @var array<ToolInterface> */
    private array $tools = [];
    private string $id;
    private array $competences = [
        WorkEnumRequired::tireReplacement,
        WorkEnumRequired::wheelBalancing,
        WorkEnumRequired::wheelReplacementBalancing,
        WorkEnumRequired::wheelInflation,
    ];
    private bool $isBusy = false;

    public function __construct(
        private readonly string $name,
        private readonly Grade $grade,
        protected LoggerInterface $logger,
        private readonly Inventory $inventory,
        private readonly GraphWork $graphWork,
        private readonly VirtualTime $time,
        /** @param array<WorkEnumRequired> $additionalCompetences */
        private array $additionalCompetences = [],
    )
    {
        if (!empty($additionalCompetences)) {
           $this->competences = array_merge($this->competences, $additionalCompetences);
        }
        $this->id = GeneratorID::genID();
    }

    /**
     * @return Grade
     */
    public function getGrade(): Grade
    {
        return $this->grade;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @throws CanNotBeExecutedException
     */
    public function do(WorkInterface $work): void
    {
        if (!$this->canExecute($work)) {
            throw new CanNotBeExecutedException();
        }

        $this->isBusy = true;

        $work->execute($this);
        $this->returnAllTools();

        $this->isBusy = false;
    }

    private function returnAllTools(): void
    {
        foreach ($this->tools as $key => $tool) {
            $this->inventory->put($this, $tool);
            unset($this->tools[$key]);
        }
    }

    public function isBusy(): bool
    {
        return $this->isBusy;
    }

    public function canExecute(WorkInterface $work): bool
    {
        if (!$this->isWorkTime($this->time->current()) === true) {
            $this->logger->log('Наш партнер сейчас не работает');
            return false;
        }

        if ($this->isBusy) {
            $this->logger->log('Я занят');
            return false;
        }

        if (!in_array($work::name(), $this->competences, true)) {
            $this->logger->log('Не умею выполнять работу: ' . $work::name()->value);
            return false;
        }

        foreach ($work->requiredTools() as $requiredTool) {
            $isToolReadyHas = false;
            foreach ($this->tools as $tool) {
                if ($tool->name() === $requiredTool) {
                    $isToolReadyHas = true;
                }
            }
            try {
                if (!$isToolReadyHas) {
                    $this->tools[] = $this->inventory->get($this, $requiredTool);
                }

            } catch (ToolNotFoundException) {
                $this->logger->log('Нет свободного ' . $requiredTool->value);
                $this->returnAllTools();

                return false;
            }
        }

        return true;
    }

    public function selectTool(ToolEnum $toolName): ToolInterface
    {
        foreach ($this->tools as $tool) {
            if ($tool::name() === $toolName) {
                return $tool;
            }
        }
        throw new \RuntimeException('Не смогли получить инструмент, который у нас есть');
    }

    public function isWorkTime(\DateTimeInterface $dateTime): bool
    {
        return $this->graphWork->isWorkTime($dateTime);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getAdditionalCompetences(): array
    {
        return $this->additionalCompetences;
    }

    /**
     * @param WorkEnumAdditional $additionalCompetencesEnum
     */
    public function addAdditionalCompetences(WorkEnumAdditional $additionalCompetencesEnum ): void
    {
        $this->additionalCompetences[] = $additionalCompetencesEnum;
    }

    /**
     * @return GraphWork
     */
    public function getGraphWork(): GraphWork
    {
        return $this->graphWork;
    }
}