<?php

require_once __DIR__.'/vendor/autoload.php';

class AbstractObject {
    public function __construct(private string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return 'abstract';
    }
}

class ConcreteObject extends AbstractObject {
    public function getType(): string
    {
        return 'concrete';
    }
}

class AbstractService {
    /**
     * @throws \Exception
     */
    public function doSomeLogic(AbstractObject $obj): ConcreteObject
    {
        echo $obj->getType() . ' ' . $obj->getName() . PHP_EOL;
        return $obj;
    }
}

class ConcreteService extends AbstractService {

    /**
     * @inheritDoc
     */
    public function doSomeLogic(AbstractObject $obj): AbstractObject
    {
        if (!$obj instanceof ConcreteObject) {
            throw new Exception();
        }

        echo $obj->getType() . ' ' . $obj->getName() . PHP_EOL;
        return $obj;
    }
}


function execute(AbstractService $abstractService, AbstractObject $obj): void
{
    try {
        $obj = $abstractService->doSomeLogic($obj);
    } catch (\Exception) {
    }
}

$service = new ConcreteService();
$obj1 = new AbstractObject('obj 1');
$obj2 = new ConcreteObject('obj 2');

execute($service, $obj1);
execute($service, $obj2);






die;
$entityManagerFactory = new \Station\Infrastructure\Doctrine\EntityManagerFactory(
    require_once __DIR__ . '/config/doctrine.php',
    require_once __DIR__ . '/config/db.php',
);


class User {
    #[OneToMany(
        targetEntity: "Phonenumber",
        mappedBy: "user",
        cascade: ["persist", "remove"],
        orphanRemoval: true)
    ]
    public $phonenumbers;
}

class PhoneNumber {

    private $user;
}

$em = $entityManagerFactory->create();
$entity1 = new \Station\Entities\TestEntity('Вася');
$em->persist($entity1);
$em->flush();



$entity1->changeName('Петю');

$em->flush();

$dsn = 'pgsql:dbname=testdb1;host=127.0.0.1';
$user = 'kirill1';
$password = '111111';
$pdo = new \PDO($dsn, $user, $password);

class Book {
    public int $id;
    public string $name;
    public ?string $description;
    public int $rating;
    public int $author_id;

    public function save(\PDO $pdo): void
    {
        $sql = <<<SQL
    INSERT INTO books (
        name,
        description,
        rating,
        author_id
   ) VALUES (
        '$this->name',
        '$this->description',
        $this->rating,
        $this->author_id
   ) RETURNING id;
SQL;
        $this->id = $pdo->query($sql)->fetchColumn();
    }

    public static function find(\PDO $pdo, string $name): Book
    {
        $stmt = $pdo->exec("select * from books where name = '$name';");
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);

        return $stmt->fetchAll()[0];
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query('select * from books;');
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);

        return $stmt->fetchAll();
    }
}

$books = Book::all($pdo);

$cleanAgile = Book::find($pdo, "';drop table books; select '");



$book = new Book();
$book->name = 'Сказка о царе султане';
$book->description = 'Сказка о царе султане - описание';
$book->rating = 5;
$book->author_id = 1;

$book->save($pdo);
$f = 1;

die;
