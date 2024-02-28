<?php

namespace Station\Vehicle;
enum DamageEnum: string
{
   case stopOut  = 'отсутствует';
   case easyDamage = 'легкое повреждение';
   case middle = 'cреднее повреждение';
   case hard = 'тяжелое повреждение';

}