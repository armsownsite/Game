<?php

namespace app\game;

class Team{
    private $a_last_step = 0;
    private $a_last_minute = 0;
    private $a_last_load = 0;
    private $m_last_step = 0;
    private $m_last_minute = 0;
    private $m_last_load = 0;
    private $d_last_step = 0;
    private $d_last_minute = 0;
    private $d_last_load = 0;
    private $new_team = [];



    ///GET THE LAST STEP OF WORKER
    public function getLastStep($teams){
        if(strtoupper($teams)=='A'){
            return $this->a_last_step;
        }
        elseif(strtoupper($teams)=='M'){
            return $this->m_last_step;
        }
        elseif(strtoupper($teams)=='D'){
            return $this->d_last_step;
        }
    }
    ///SET THE LAST STEP OF WORKER
    public function setLastStep($teams,$step){
        if(strtoupper($teams)=='A'){
            return $this->a_last_step = $step;
        }
        elseif(strtoupper($teams)=='M'){
            return $this->m_last_step = $step;
        }
        elseif(strtoupper($teams)=='D'){
            return $this->d_last_step = $step;
        }
    }
    ///GET THE LAST LOAD OF WORKER
    public function getLastLoad($teams){
        if(strtoupper($teams)=='A'){
            return $this->a_last_load;
        }
        elseif(strtoupper($teams)=='M'){
            return $this->m_last_load;
        }
        elseif(strtoupper($teams)=='D'){
            return $this->d_last_load;
        }
    }
    ///SET THE LAST LOAD OF WORKER
    public function setLastLoad($teams,$load){
        if(strtoupper($teams)=='A'){
            return $this->a_last_load = $load;
        }
        elseif(strtoupper($teams)=='M'){
            return $this->m_last_load = $load;
        }
        elseif(strtoupper($teams)=='D'){
            return $this->d_last_load = $load;
        }
    }
    ///GET THE LAST WORK MINUTE OF WORKER
    public function getlastMinute($teams){
        if(strtoupper($teams)=='A'){            
            return $this->a_last_minute;
        }
        elseif(strtoupper($teams)=='M'){
            return $this->m_last_minute;
        }
        elseif(strtoupper($teams)=='D'){
            return $this->d_last_minute;
        }
    }
   ///SET THE LAST WORK MINUTE OF WORKER
    public function setlastMinute($teams,$minute){
        if(strtoupper($teams)=='A'){        
            return $this->a_last_minute = $minute;
        }
        elseif(strtoupper($teams)=='M'){
            return $this->m_last_minute = $minute;
        }
        elseif(strtoupper($teams)=='D'){
            return $this->d_last_minute = $minute;
        }
    }

    ///THIS METHOD RETURN ARRAY WITH CLASS PERSON
    public function getTeamDetails($team){   
        foreach($team as $person){
            switch (strtoupper($person)) {
                case "A":
                    $person = new A ;
                    array_push($this->new_team,$person);
                    break;
                case "M":
                    $person = new M ;
                    array_push($this->new_team,$person);
                    break;
                case "D":
                    $person = new D ;
                    array_push($this->new_team,$person);
                    break;
            }
        }
        return $this->new_team;
    }
}
