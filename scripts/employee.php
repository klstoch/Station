<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/functions.php';

use Station\Employ\Grade;
use Station\Employ\Graph\ConstantGraphWork;
use Station\Employ\Graph\SlidingGraphWork;
use Station\Employ\JobContract;
use Station\Employ\TimeInterval\GraphIntervals;
use Station\Employ\TimeInterval\Time;
use Station\Employ\TimeInterval\TimeInterval;
use Station\Employ\TyreMechanic;
use Station\Infrastructure\EnumDayOfWeek;
use Station\Infrastructure\IO\IOFactory;
use Station\Logger\EchoLogger;
use Station\Logger\LoggerWithTiming;
use Station\Mutex\Mutex;
use Station\PilotStation\RedisBasedStationRepository;
use Station\Work\CompetenceEnum;

$redis = new \Station\Infrastructure\Cache\Redis();

$stationRepository = new RedisBasedStationRepository($redis, new Mutex($redis));

$ioFactory = new IOFactory();
$io = $ioFactory->create();

$timeStartWorking = ['08:00', '08:15', '08:30', '08:45', '09:00', '09:15', '09:30', '09:45', '10:00'];
$timeFinalWorking = ['18:00', '18:15', '18:30', '18:45', '19:00', '19:15', '19:30', '19:45', '20:00', '20:15', '20:30', '20:45', '21:00'];
$timeLaunch = ['12:00', '12:15', '12:30', '13:00', '13:15', '13:30', '13:45', '14:00'];

$constantGraphWork = 'Постоянный график работы';
$slidingGraphWork = 'Плавающий график работы';
$graphs = [$constantGraphWork, $slidingGraphWork];

$answerByEmployees = ['Создать нового сотрудника', 'Редактировать данные существующего сотрудника'];

$station = selectStation($io, $stationRepository);
$employees = $station->getEmployeeRepository()->getAll();

$isEmployeesExists = !empty($employees);
$isNeedCreate = !$isEmployeesExists
    || $io->requestInput('Желаете создать нового сотрудника или внести изменения у существующего? ', $answerByEmployees, 'Создать нового сотрудника') === 'Создать нового сотрудника';
if ($isNeedCreate) {
    $name = readline('Введи ФИО сотрудника: ' . PHP_EOL);
    $gradeName = $io->requestInput('Выбери уровень квалификации сотрудника', array_map(static fn(Grade $enumGrade) => $enumGrade->value, Grade::cases()));
    $grade = Grade::from($gradeName);

    $graphWork = $io->requestInput('Выбери график работы сотрудника: ', $graphs);
    if ($graphWork === $constantGraphWork) {
        $config = [];
        for ($dayOfWeek = 1; $dayOfWeek <= 7; $dayOfWeek++) {
            $dayTitle = EnumDayOfWeek::from($dayOfWeek)->title();
            $config[$dayOfWeek] = new GraphIntervals(
                workingTimeInterval: new TimeInterval(
                    Time::from($io->requestInput("Введи время начала работы ($dayTitle)", $timeStartWorking, '08:00')),
                    Time::from($io->requestInput("Введи время окончания рабочего дня ($dayTitle)", $timeFinalWorking, '20:00')),
                ),
                launchTimeInterval: new TimeInterval(
                    Time::from($io->requestInput('Введи начало обеденного перерыва ', $timeLaunch, '12:30')),
                    Time::from($io->requestInput('Введи время окончания обеденного перерыва:', $timeLaunch, '13:00')),
                )
            );
        }
        $graphWork = new ConstantGraphWork($config);
    } else {
        $workingDays = (int)readline('Введи количество рабочих дней подряд: ' . PHP_EOL);
        $holidays = (int)readline('Введи количество выходных дней подряд: ' . PHP_EOL);
        $workingTime = new GraphIntervals(
            workingTimeInterval: new TimeInterval(
                Time::from($io->requestInput('Введи начало рабочего дня', $timeStartWorking, '08:00')),
                Time::from($io->requestInput('Введи время окончания рабочего дня', $timeFinalWorking, '20:00')),
            ),
            launchTimeInterval: new TimeInterval(
                Time::from($io->requestInput('Введи начало обеденного перерыва ', $timeLaunch, '12:30')),
                Time::from($io->requestInput('Введи время окончания обеденного перерыва:', $timeLaunch, '13:00')),
            ));
        $firstWorkDay = new \DateTimeImmutable(readline('Введи первый рабочий день в формате Год-месяц-день (2024-01-01): '));
        $graphWork = new SlidingGraphWork($workingDays, $holidays, $workingTime, $firstWorkDay,);
    }

    $time = $station->getTime();
    $inventory = $station->getInventory();
    $logger = new LoggerWithTiming($time, new EchoLogger());
    $salaryRate = (float)readline('Введи оклад сотрудника: ');
    $interestRate = (float)readline('Введи процентную ставку заработной платы: ');
    $jobContract = new JobContract($salaryRate, $interestRate);

    $employee = new TyreMechanic($name, $grade, $logger, $inventory, $graphWork, $time, $jobContract);

    $station->getEmployeeRepository()->saveEmployee($employee);//addEmployee($employee);
    //$stationRepository->saveEmployees($station);
} else {
    $employee = selectEmploy($station, $io);
}

$answerByEmployee = ['Желаете изменить зарплату?', 'Желаете добавить умения сотруднику?'];

while (true) {
    $inputCorrection = $io->requestInput('Внести дополнительные корректировки? ', $answerByEmployee);
    if ($inputCorrection === 'Желаете изменить зарплату?') {
        $salaryRate = (float)readline('Введи оклад сотрудника: ');
        $interestRate = (float)readline('Введи процентную ставку заработной платы: ');
        $jobContract = $employee->getJobContract(); //new JobContract($salaryRate, $interestRate);
        $jobContract->updateSalaryRate($salaryRate); // изменить название методов
        $jobContract->updateInterestRate($interestRate);

        $station->getEmployeeRepository()->saveEmployee($employee);

    } elseif ($inputCorrection === 'Желаете добавить умения сотруднику?') {
        $competencesToSelect = [];
        foreach (CompetenceEnum::cases() as $competence) {
            if (!in_array($competence, $employee->getCompetences())) {
                $competencesToSelect[] = $competence;
            }
        }
        $workEnum = CompetenceEnum::from($io->requestInput('Введите дополнительное умение: ', array_map(static fn(CompetenceEnum $workEnumAdditional) => $workEnumAdditional->value, $competencesToSelect)));
        $employee->addAdditionalCompetences($workEnum);

        $station->getEmployeeRepository()->saveEmployee($employee);//$stationRepository->saveEmployees($station);
    }
}






