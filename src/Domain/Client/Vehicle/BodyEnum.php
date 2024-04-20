<?php

namespace Station\Domain\Client\Vehicle;
enum BodyEnum: string
{
    case sedan = 'седан, хэтчбек, лифтбек, универсал';
    case coupe = 'купе';
    case crossover = 'кроссовер';
    case offroad = 'внедорожник';
    case minibus = 'микроавтобус';
}
