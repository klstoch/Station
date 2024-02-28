<?php

declare(strict_types=1);

namespace Station\Employ;

use Station\Inventory;
use Station\Logger\LoggerInterface;
use Station\Tool\ToolEnum;
use Station\Tool\ToolInterface;
use Station\ToolNotFoundException;
use Station\Work\WorkEnum;
use Station\Work\WorkInterface;

final class TyreMechanic extends AbstractEmploy
{
//   private bool $isBusy = false;
//
//    private array $competences = [
//        WorkEnum::tireReplacement,
//        WorkEnum::wheelBalancing,
//        WorkEnum::wheelReplacementBalancing,
//        WorkEnum::wheelInflation,
//    ];
//
//    /** @var array<ToolInterface> */
//    private array $tools = [];
//
//    public function __construct(
//        private readonly Inventory $inventory,
//        private readonly string $name,
//        LoggerInterface $logger,
//        /** @param array<WorkEnum> $additionalCompetences */
//        array $additionalCompetences = [],
//    ) {
//        if (!empty($additionalCompetences)) {
//            $this->competences = array_merge($this->competences, $additionalCompetences);
//        }
//        parent::__construct($logger);
//    }
//
//    public function name(): string
//    {
//        return $this->name;
//    }
//
//    /**
//     * @throws CanNotBeExecutedException
//     */
//    public function do(WorkInterface $work): void
//    {
//        if (!$this->canExecute($work)) {
//            throw new CanNotBeExecutedException();
//        }
//
//        $this->isBusy = true;
//
//        $work->execute($this);
//        $this->returnAllTools();
//
//        $this->isBusy = false;
//    }
//
//    private function returnAllTools(): void
//    {
//        foreach ($this->tools as $key => $tool) {
//            $this->inventory->put($this, $tool);
//            unset($this->tools[$key]);
//        }
//    }
//
//    public function isBusy(): bool
//    {
//        return $this->isBusy;
//    }
//
//    public function canExecute(WorkInterface $work): bool
//    {
//        if ($this->isBusy) {
//            $this->logger->log('Я занят');
//            return false;
//        }
//
//        if (!in_array($work::name(), $this->competences, true)) {
//            $this->logger->log('Не умею выполнять работу: ' . $work::name()->value);
//            return false;
//        }
//
//        foreach ($work->requiredTools() as $requiredTool) {
//            try {
//                $this->tools[] = $this->inventory->get($this, $requiredTool);
//            } catch (ToolNotFoundException) {
//                $this->logger->log('Нет свободного ' . $requiredTool->value);
//                $this->returnAllTools();
//
//                return false;
//            }
//        }
//
//        return true;
//    }
//
//    public function selectTool(ToolEnum $toolName): ToolInterface
//    {
//        foreach ($this->tools as $tool) {
//            if ($tool::name() === $toolName) {
//                return $tool;
//            }
//        }
//        throw new \RuntimeException('Не смогли получить инструмент, который у нас есть');
//    }
}
