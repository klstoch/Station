<?php

namespace Station\Domain\Station\Employ;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Station\Domain\AbstractEntity;
use Station\Domain\Action;
use Station\Domain\Event\EventDispatcherInterface;
use Station\Domain\Station\Employ\CanNotBeExecutedException;
use Station\Domain\Station\Inventory\ToolNotFoundException;
use Station\Domain\Station\Employ\Event\EmployActionFinishEvent;
use Station\Domain\Station\Employ\Event\EmployActionStartEvent;
use Station\Domain\Station\Employ\Event\EmployCanNotDoWorkEvent;
use Station\Domain\Station\Employ\Event\EmployIsBusyEvent;
use Station\Domain\Station\Employ\Event\EmployIsNotWorkingEvent;
use Station\Domain\Station\Employ\Schedule\ScheduleInterface;
use Station\Domain\Station\Inventory\InventoryInterface;
use Station\Domain\Station\Station;
use Station\Domain\Time\Duration;
use Station\Domain\Time\VirtualTime;
use Station\Domain\Tool\ToolEnum;
use Station\Domain\Tool\ToolInterface;
use Station\Domain\Work\CompetenceEnum;
use Station\Domain\Work\WorkInterface;

#[Entity]
#[Table(name: 'employees')]
#[InheritanceType('SINGLE_TABLE')]
#[DiscriminatorColumn(name: 'speciality', type: 'string')]
#[DiscriminatorMap([
    'tyre-mechanic' => TyreMechanic::class,
])]
abstract class AbstractEmploy extends AbstractEntity implements EmployInterface
{
    protected array $competences = [];
    private bool $isBusy = false;

    /** @var array<ToolInterface> */
    private array $tools = [];
    public function __construct(
        #[Column(name:'name', type: 'string', length: 150)]
        private readonly string $name,

        #[Column(name:'grade', type: 'grade')]
        private readonly Grade $grade,

        #[Column(name: 'time', type: 'virtual_time')]
        private readonly VirtualTime $time,

        #[ManyToOne(targetEntity: Station::class, inversedBy: 'employees')]
        #[JoinColumn(name: 'station_id', referencedColumnName: 'id', nullable: false)]
        private readonly Station $station,

        /** @var array<JobContract> */
        #[OneToMany(targetEntity: JobContract::class, mappedBy: 'employ')]
        private readonly array $jobContracts,

        /** @param array<CompetenceEnum> $additionalCompetences */
        #[Column(name: 'additional_competences', type: 'competences')]
        private array $additionalCompetences = [],

        ?string $id = null,
    ) {
        parent::__construct($id);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Grade
     */
    public function getGrade(): Grade
    {
        return $this->grade;
    }

    public function doWork(
        WorkInterface $work,
        InventoryInterface $inventory,
        EventDispatcherInterface $eventDispatcher,
    ): void {
        if (!$this->canExecute($work, $inventory, $eventDispatcher)) {
            throw new CanNotBeExecutedException();
        }

        $this->isBusy = true;

        $work->execute($this, $eventDispatcher);
        $this->returnAllTools($inventory);

        $this->isBusy = false;
    }

    public function doAction(Action $action, EventDispatcherInterface $eventDispatcher): void
    {
        $eventDispatcher->dispatch(new EmployActionStartEvent($this, $action->title));
        $this->time->wait($action->duration);
        $eventDispatcher->dispatch(new EmployActionFinishEvent($action->title));
    }

    private function returnAllTools(InventoryInterface $inventory): void
    {
        foreach ($this->tools as $key => $tool) {
            $inventory->put($this, $tool);
            unset($this->tools[$key]);
        }
    }

    public function isBusy(): bool
    {
        return $this->isBusy;
    }

    public function canExecute(
        WorkInterface $work,
        InventoryInterface $inventory,
        EventDispatcherInterface $eventDispatcher,
    ): bool {
        if (!$this->isWorkTime()) {
            $eventDispatcher->dispatch(new EmployIsNotWorkingEvent($this));
            return false;
        }

        if ($this->isBusy) {
            $eventDispatcher->dispatch(new EmployIsBusyEvent($this));
            return false;
        }

        foreach ($work->requiredCompetences() as $competence) {
            if (!in_array($competence, $this->getCompetences(), true)) {
                $eventDispatcher->dispatch(new EmployCanNotDoWorkEvent($this, $work, 'Недостаточно компетенций'));
                return false;
            }
        }

        foreach ($work->requiredTools() as $requiredTool) {
            $isToolAlreadyHas = false;
            foreach ($this->tools as $tool) {
                if ($tool::name() === $requiredTool) {
                    $isToolAlreadyHas = true;
                    break;
                }
            }
            try {
                if (!$isToolAlreadyHas) {
                    $this->tools[] = $inventory->get($this, $requiredTool);
                }
            } catch (ToolNotFoundException) {
                $eventDispatcher->dispatch(new EmployCanNotDoWorkEvent($this, $work, 'Нет свободного ' . $requiredTool->value));
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
        return $this->getSchedule()->isWorkTime($dateTime);
    }

    /**
     * @return array<CompetenceEnum>
     */
    public function getCompetences(): array
    {
        return array_merge($this->competences, $this->additionalCompetences);
    }

    public function addAdditionalCompetences(CompetenceEnum $competenceEnum): void
    {
        $this->competences[] = $competenceEnum;
    }

    public function getSchedule(): ScheduleInterface
    {
        foreach ($this->jobContracts as $contract) {
            if ($contract->isActive()) {
                return $contract->getSchedule();
            }
        }
        throw new \DomainException(sprintf('Employ "%s" has not active contract', $this->id));
    }

    public function doBreak(EventDispatcherInterface $eventDispatcher): void
    {
        $actions = [
            'Вышел на перекур',
            'Пошел за чайком',
            'Точит лясы',
            'Убирает рабочее место',
        ];

        $break = new Action(
            title: $actions[random_int(0, count($actions) - 1)],
            duration: Duration::m(random_int(1, 10)),
        );

        $this->doAction($break, $eventDispatcher);
    }

    public function addAdditionalCompetence(CompetenceEnum $competence): void
    {
        if (!in_array($competence, $this->additionalCompetences, true)) {
            $this->additionalCompetences[] = $competence;
        }
    }
}