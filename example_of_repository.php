<?php

use Station\BaseClient\ClientBase;
use Station\Employ\EmployeeRepository;
use Station\Employ\Graph\ConstantGraphWork;
use Station\Employ\TimeInterval\GraphIntervals;
use Station\Employ\TimeInterval\TimeInterval;
use Station\Infrastructure\EnumDayOfWeek;
use Station\Inventory\Inventory;
use Station\PilotStation\Station;
use Station\Queue\ClientQueue;

class StationRepository
{
    public function __construct(
        private readonly \PDO $pdo,
        private readonly Inventory $inventory,
        private readonly ClientQueue $clientQueue,
        private readonly ClientBase $clientBase,
        private readonly EmployeeRepository $employeeRepository,
    ) {
    }

    /**
     * @return array<Station>
     */
    public function all(): array
    {
        $result = [];
        $stationsData = $this->pdo->query('select * from stations;')->fetchAll();
        foreach ($stationsData as $stationData) {
            $days = [];
            foreach (EnumDayOfWeek::cases() as $dayOfWeek) {
                $graphWork = $this->pdo->query(<<<SQL
    select *
    from station_graph_work
    where
        station_id = {$stationData['id']}
        and day_of_week = $dayOfWeek->value
    limit 1
SQL)->fetchAll();
                $days[] = new GraphIntervals(
                    workingTimeInterval: new TimeInterval(
                        start: $graphWork['start_time'],
                        end: $graphWork['final_time'],
                    ),
                );
            }
            $graphWork = new ConstantGraphWork($days);

            $time = new \Station\Time\VirtualTime(
                $stationData['start_real_time'],
                $stationData['start_virtual_time'],
                $stationData['virtual_time_scale'],
            );

            $result[] = new Station(
                $stationsData['name'],
                $stationData['address'],
                $graphWork,
                $this->inventory,
                $this->clientQueue,
                $time,
                $this->clientBase,
                $this->employeeRepository,
            );
        }

        return $result;
    }
}