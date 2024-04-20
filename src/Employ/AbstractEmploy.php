<?php

namespace Station\Employ;


use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Station\Infrastructure\GeneratorID;
use Station\Employ\Graph\GraphWork;
use Station\Exception\CanNotBeExecutedException;
use Station\Exception\ToolNotFoundException;
use Station\Inventory\Inventory;
use Station\Logger\LoggerInterface;
use Station\PilotStation\Station;
use Station\Time\VirtualTime;
use Station\Tool\ToolEnum;
use Station\Tool\ToolInterface;
use Station\Work\CompetenceEnum;
use Station\Work\WorkInterface;
#[Entity]
#[Table(name: 'employees')]
abstract class AbstractEmploy implements EmployInterface
{
    /** @var array<ToolInterface> */
    private array $tools = [];
    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCES')]
    #[Column(name: 'id', type: 'integer', nullable: false)]
    private string $id;
    protected array $competences = [];
    private bool $isBusy = false;

    public function __construct(
        #[Column(name:'employee_name', length: 150, nullable:false)]
        private readonly string $name,
        #[Column(name:'grade', type: 'grade',length: 50, nullable:false)]
        private readonly Grade $grade,
        protected LoggerInterface $logger,
        private readonly Inventory $inventory,
        #[Column(name: 'graph_work', type: 'graph_work', nullable: false)]
        private readonly GraphWork $graphWork,
        private readonly VirtualTime $time,
        private readonly JobContract $jobContract,
        #[ManyToOne(targetEntity: Station::class, inversedBy: 'employees')]
        private readonly Station $station,
        /** @param array<CompetenceEnum> $additionalCompetences */
        array $additionalCompetences = [],
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
        if (!$this->isWorkTime()) {
            $this->logger->log('Наш партнер сейчас не работает');
            return false;
        }

        if ($this->isBusy) {
            $this->logger->log('Я занят');
            return false;
        }

        foreach ($work->requiredCompetences() as $competence) {
            if (!in_array($competence, $this->competences)) {
                $this->logger->log('Не умею выполнять работу, такую как: ' . $work::name());
                return false;
            }
        }

        foreach ($work->requiredTools() as $requiredTool) {
            $isToolAlreadyHas = false;
            foreach ($this->tools as $tool) {
                if ($tool->name() === $requiredTool) {
                    $isToolAlreadyHas = true;
                    break;
                }
            }
            try {
                if (!$isToolAlreadyHas) {
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

    public function isWorkTime(?\DateTimeInterface $dateTime = null): bool
    {
        $dateTime ??= $this->time->current();
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
     * @return array<CompetenceEnum>
     */
    public function getCompetences(): array
    {
        return $this->competences;
    }

    /**
     * @param CompetenceEnum $competenceEnum
     */
    public function addAdditionalCompetences(CompetenceEnum $competenceEnum): void
    {
        $this->competences[] = $competenceEnum;
    }

    /**
     * @return GraphWork
     */
    public function getGraphWork(): GraphWork
    {
        return $this->graphWork;
    }

    public function doBreak(): void
    {
        $actions = [
            'Вышел на перекур',
            'Пошел за чайком',
            'Точит лясы',
            'Убирает рабочее место',
        ];
        $this->logger->log($actions[random_int(0, count($actions) - 1)]);
        $this->time->wait(minute: random_int(1, 10));
        $this->logger->log('вернулся');
    }

    /**
     * @return JobContract
     */
    public function getJobContract(): JobContract
    {
        return $this->jobContract;
    }

    /**
     * @return Station
     */
    public function getStation(): Station
    {
        return $this->station;
    }
}