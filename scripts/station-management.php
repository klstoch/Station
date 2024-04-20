<?php

declare(strict_types=1);

use Doctrine\ORM\Mapping\ClassMetadata;
use Station\BaseClient\ClientBaseImp;
use Station\BaseClient\ClientBaseRepository;
use Station\Employ\EmployeeRepository;
use Station\Employ\Graph\ConstantGraphWork;
use Station\Employ\TimeInterval\GraphIntervals;
use Station\Employ\TimeInterval\Time;
use Station\Employ\TimeInterval\TimeInterval;
use Station\Infrastructure\Doctrine\EntityManagerFactory;
use Station\Infrastructure\EnumDayOfWeek;
use Station\Infrastructure\IO\IOFactory;
use Station\Inventory\RedisBasedInventory;
use Station\Logger\EchoLogger;
use Station\Logger\LoggerWithTiming;
use Station\Mutex\Mutex;
use Station\PilotStation\Station;
use Station\PilotStation\RedisBasedStationRepository;
use Station\Queue\RedisBasedClientQueue;
use Station\Time\VirtualTime;
use Station\Tool\AirGun;
use Station\Tool\BalancingMachine;
use Station\Tool\Compressor;
use Station\Tool\TyreChangingMachine;
use Station\Tool\ToolEnum;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/functions.php';

$entityManagerFactory = new EntityManagerFactory(
    require_once __DIR__ . '/../config/doctrine.php',
    require_once __DIR__ . '/../config/db.php',
);
$em = $entityManagerFactory->create();
$classMetaDataStation = new ClassMetadata('Station');
$stationEntityRepository = new \Station\PilotStation\StationRepository($em,$classMetaDataStation);

$redis = new \Station\Infrastructure\Cache\Redis();

$ioFactory = new IOFactory();
$io = $ioFactory->create();

$stationRedisRepository = new RedisBasedStationRepository($redis, new Mutex($redis));
$time = new VirtualTime(microtime(true), new DateTimeImmutable(readline('Введи время планируемое для запуска: ')),360);
$logger = new LoggerWithTiming($time, new EchoLogger());

$mutex1 = new Mutex($redis, 'RedisBasedClientQueue');

$timeStart = ['08:00', '08:15', '08:30', '08:45', '09:00', '09:15', '09:30', '09:45', '10:00'];
$timeFinal = ['18:00', '18:15', '18:30', '18:45', '19:00', '19:15', '19:30', '19:45', '20:00', '20:15', '20:30', '20:45', '21:00'];

$config = [];
for ($dayOfWeek = 1; $dayOfWeek <= 7; $dayOfWeek++) {
    $dayTitle = EnumDayOfWeek::from($dayOfWeek)->title();
    $config[$dayOfWeek] = new GraphIntervals(
        workingTimeInterval: new TimeInterval(
            Time::from($io->requestInput("Введи время начала работы ($dayTitle)", $timeStart, '08:00')),
            Time::from($io->requestInput("Введи время окончание работы ($dayTitle)", $timeFinal, '20:00')),
        ),
    );
}

$graph = new ConstantGraphWork($config);

$answerByStation = ['Создать новую станцию', 'Внести изменения в существующую станцию'];
$answerByInventory = ['Создать новый инвентарь', 'Использовать существующий'];

$isStationsExists = !empty($stationRedisRepository->getAll());

$isNeedCreate = !$isStationsExists
    || $io->requestInput('Желаете создать новую станцию или внести изменения в существующую? ', $answerByStation, 'Создать новую станцию') === 'Создать новую станцию';

if ($isNeedCreate) {
    $name = readline('Введи название предприятия: ' . PHP_EOL);
    $address = readline('Введи адрес оказания услуг: ' . PHP_EOL);
    $isNeedCreate = !$isStationsExists
        || $io->requestInput('Желаете создать новый инвентарь или использовать существующий? ', $answerByInventory, 'Создать новый инвентарь') === 'Создать новый инвентарь';
    if ($isNeedCreate) {
        $mutex2 = new Mutex($redis, 'RedisBasedInventory');
        $inventory = new RedisBasedInventory($logger, $redis, $mutex2);
    } else {
        $inventory = selectStation($io, $stationRedisRepository)->getInventory();
    }
    $clientBaseRepository = new ClientBaseRepository($redis);
    $clientQueue = new RedisBasedClientQueue($redis, $mutex1);
    $clientBase = new ClientBaseImp($clientBaseRepository);
    $employRepository = new EmployeeRepository($redis);
    $stationEntity = new Station($name, $address, $graph, $inventory, $clientQueue, $time, $clientBase);
    //$stationRedisRepository->save($stationEntity);
    $stationEntityRepository->save($stationEntity);
    //$em->persist($stationEntity);
    //$em->flush();

} else {
    $stationEntity = selectStation($io, $stationRedisRepository);
}


while (true) {
    $toolName = $io->requestInput('Выбери инструмент', array_map(static fn(ToolEnum $enum) => $enum->value, ToolEnum::cases()));
    $tool = match ($toolName) {
        ToolEnum::compressor->value => new Compressor($time, $logger),
        ToolEnum::tireChangingMachine->value => new TyreChangingMachine($time, $logger),
        ToolEnum::airGun->value => new AirGun($time, $logger),
        ToolEnum::balancingMachine->value => new BalancingMachine($time, $logger)
    };

    $stationEntity->getInventory()->addNew($tool);
}



