<?php

namespace app\game;

use Codeception\Step;

class A
{ 
    public $repair_steps = 10;
    public $max_load = 7;
    public $load_minus = 2;
    public $work_time = 10;
    public $type = 'A';
}

class M
{  
    public $repair_steps = 2;
    public $max_load = 3;
    public $load_minus = 1;
    public $work_time = 2;
    public $type = 'M';

}

class D
{  
    public $repair_steps = 1;
    public $max_load = 1;
    public $load_minus = 0;
    public $work_time = 1;
    public $type = 'D';
}


class Parser{  
    public function teamParse($team){
        $team = str_split($team);      
        return $team;
    }
}






