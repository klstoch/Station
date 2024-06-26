<?php

namespace Station\Domain\ClientTraffic;

use Station\Domain\Client\Client;
use Station\Domain\Client\Vehicle\BodyEnum;
use Station\Domain\Client\Vehicle\Car;
use Station\Domain\Client\Vehicle\DamageEnum;
use Station\Domain\Client\Vehicle\DiscMaterialEnum;
use Station\Domain\Client\Vehicle\DiscWheel;
use Station\Domain\Client\Vehicle\RadiusEnum;
use Station\Domain\Client\Vehicle\Tyre;
use Station\Domain\Client\Vehicle\Wheel;

class ClientTraffic
{
    private const NAMES_LIST = [
        'Абрамова Марина Фёдоровна',
        'Смирнова Алиса Матвеевна',
        'Гусева Дарья Дмитриевна',
        'Титов Святослав Ильич',
        'Шестакова Мария Матвеевна',
        'Калинин Макар Михайлович',
        'Широков Евгений Михайлович',
        'Малахов Евгений Адамович',
        'Тихонова Мария Алексеевна',
        'Демьянов Матвей Александрович',
        'Литвинов Степан Львович',
        'Королев Алексей Артёмович',
        'Смирнова Вероника Никитична',
        'Горелова Марьям Артемьевна',
        'Тимофеев Семён Денисович',
        'Орлова Дарья Тимуровна',
        'Поляков Тимофей Михайлович',
        'Васильева София Ильинична',
        'Костина Ксения Давидовна',
        'Комаров Артём Георгиевич',
        'Петрова Екатерина Артёмовна',
        'Иванова Ясмина Александровна',
        'Новиков Артемий Львович',
        'Устинова Елена Николаевна',
        'Кононов Михаил Иванович',
        'Сорокин Фёдор Дмитриевич',
        'Новикова Ева Егоровна',
        'Пименов Александр Никитич',
        'Максимова Ангелина Ильинична',
        'Александров Эмиль Владимирович',
        'Некрасова Мила Никитична',
        'Петров Савва Романович',
        'Астахова Виктория Арсентьевна',
        'Беспалова Таисия Артёмовна',
        'Емельянова Ксения Андреевна',
        'Андреев Мирон Николаевич',
        'Мартынов Артём Сергеевич',
        'Константинов Александр Никитич',
        'Спиридонов Ярослав Никитич',
        'Журавлев Давид Святославович',
        'Волков Роман Михайлович',
        'Рыбакова Василиса Артёмовна',
        'Андреев Дмитрий Михайлович',
        'Яшин Владимир Константинович',
        'Корнев Максим Матвеевич',
        'Бирюков Владислав Фёдорович',
        'Чистяков Андрей Алексеевич',
        'Царев Андрей Тимофеевич',
        'Исаева Милана Александровна',
        'Исаев Тихон Александрович',
        'Ковалев Марк Иванович',
        'Филатова Екатерина Кирилловна',
        'Кузнецов Александр Михайлович',
        'Максимова Арина Михайловна',
        'Рябова Мария Артёмовна',
        'Кошелева Николь Львовна',
        'Нефедова Милана Николаевна',
        'Овчинников Матвей Максимович',
        'Прохорова Софья Михайловна',
        'Старостина София Данииловна',
        'Наумов Артём Богданович',
        'Быков Максим Демидович',
        'Богомолов Дмитрий Денисович',
        'Антонов Сергей Маркович',
        'Митрофанов Матвей Вадимович',
        'Королев Давид Максимович',
        'Лаврентьев Захар Павлович',
        'Столярова Ева Константиновна',
        'Моисеева Нина Никитична',
        'Румянцев Роман Рафаэльевич',
        'Мухина Анастасия Артемьевна',
        'Афанасьева Ульяна Андреевна',
        'Куликов Дамир Матвеевич',
        'Давыдов Али Ильич',
        'Суворова Анна Александровна',
        'Рудаков Лев Даниилович',
        'Жданова Варвара Артёмовна',
        'Беляева Валерия Львовна',
        'Афанасьев Виктор Петрович',
        'Сидоров Денис Иванович',
        'Курочкин Александр Артёмович',
        'Наумов Фёдор Савельевич',
        'Карпов Дмитрий Дмитриевич',
        'Спиридонов Константин Савельевич',
        'Воробьева Ева Макаровна',
        'Савельев Александр Георгиевич',
        'Спиридонов Фёдор Николаевич',
        'Лукин Семён Тимофеевич',
        'Комаров Ярослав Львович',
        'Дубинина Аиша Львовна',
        'Добрынин Мирослав Даниилович',
        'Павлова Агата Леонидовна',
        'Соколов Давид Андреевич',
        'Мельников Николай Егорович',
        'Смирнов Алексей Кириллович',
        'Смирнова Ольга Максимовна',
        'Лебедев Демид Михайлович',
        'Овчинников Сергей Никитич',
        'Беляева Анна Эмировна',
        'Ковалев Николай Иванович',
    ];

    public function getClient(): Client
    {
        $name = $this->createName();
        $car = $this->createCar();
        return new Client($name, $car);
    }

    private function createName(): string
    {
        $key = random_int(0, count(self::NAMES_LIST) - 1);
        return self::NAMES_LIST[$key];
    }

    private function createCar(): Car
    {
        $wheel = $this->getWheel();
        $body = $this->getBody();
        return new Car($wheel, $body);
    }

    private function getBody(): BodyEnum
    {
        $key = random_int(0, count(BodyEnum::cases()) - 1);
        return BodyEnum::cases()[$key];
    }

    private function getWheel(): Wheel
    {
        $radius = $this->getRadius();
        $discWheel = $this->getDiscWheel($radius);
        $tyre = $this->getTyre($radius);
        return new Wheel($discWheel, $tyre);
    }

    private function getDiscWheel(RadiusEnum $radius): DiscWheel
    {
        $damage = $this->getDamage();
        $discMaterial = $this->getDiscMaterial();
        return new DiscWheel($damage, $discMaterial, $radius);
    }

    private function getDamage(): DamageEnum
    {
        $key = random_int(0, count(DamageEnum::cases()) - 1);
        return DamageEnum::cases()[$key];
    }

    private function getDiscMaterial(): DiscMaterialEnum
    {
        $key = random_int(0, count(DiscMaterialEnum::cases()) - 1);
        return DiscMaterialEnum::cases()[$key];
    }

    private function getTyre(RadiusEnum $radius): Tyre
    {
        $isRun_flat = (bool)random_int(0,1);
        return new Tyre($isRun_flat, $radius);
    }

    private function getRadius(): RadiusEnum
    {
        $key = random_int(0, count(RadiusEnum::cases()) - 1);
        return RadiusEnum::cases()[$key];
    }
}