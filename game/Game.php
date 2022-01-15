<?php
namespace app\game;
use app\game\Team;
class Game{

                         //// Shows Passed minutes
    private $passed_minutes=0;
                        //// Shows Load KG
    private $load=0;
                        //// It is Total Load KG
    private $totalLaod=200;
                         //// This is Team OBJ
    private $team_score;
                        //// This is Steps
    private $steps = 0;
                         //// Shows when games was finished
    private $finish = 0;



                        ////this method return team finished minutes
    public function getTeamScore($teams,$passedMinutes){
        $this->passed_minutes = $passedMinutes;
        if($this->passed_minutes==0){
                echo " _________________________________ Start Working ___________________________________ ".PHP_EOL;
                $this->load = 0;
                $this->steps = 0;
                $this->totalLaod = 200;
        }
        $this->team_score = $teams;
        $teamObj = new Team($this->team_score);
        foreach($this->team_score as $team){
            $this->steps ++;
            // WHEN IT IS THE BEGINING OG THE WORK
            if($teamObj->getLastLoad($team->type)==0 || $teamObj->getLastStep($team->type)==0 || ($this->passed_minutes==0)){         
                $this->load += $team->max_load;     
                $teamObj->setLastLoad($team->type,$team->max_load);  
            }// WHEN THE WORKER WAS REPAIRED
            elseif((($teamObj->getLastStep($team->type))+($team->repair_steps))<=$this->steps){
                $this->load += $team->max_load;
                $teamObj->setLastLoad($team->type,$team->max_load);  
            }  
            else{
                //  WHEN THE WORKER IS TIRED
                if(($teamObj->getLastLoad($team->type))-($team->load_minus)>0){
                    $this->load += ($teamObj->getLastLoad($team->type))-($team->load_minus);  
                    $teamObj->setLastLoad($team->type,($teamObj->getLastLoad($team->type))-($team->load_minus));
                }
                else{
                    $this->load += 0; 
                    $teamObj->setLastLoad($team->type,0);
                }
            }
            $this->passed_minutes += $team->work_time;      
            $teamObj->setLastStep($team->type,$this->steps);
            $teamObj->setlastMinute($team->type,$this->passed_minutes);
            $teamObj->getlastMinute($team->type);                 
            echo " Now is Working Person - ".$team->type." who works - ".$teamObj->getlastMinute($team->type)." his load is ".$teamObj->getLastLoad($team->type)." kg".PHP_EOL;
            if(($this->load)>=($this->totalLaod)){
                echo " _________________________________ End   Working ___________________________________ ".PHP_EOL;
                return $this->passed_minutes;
            }
        }
        if(($this->load)<($this->totalLaod)){
                return $this->getTeamScore($this->team_score,$this->passed_minutes);
        }
        else{
            echo " _________________________________ End   Working ___________________________________ ".PHP_EOL;
                return $this->passed_minutes;
        }
    }


    ////// THIS METHOD SHOWS RESULTS
    public function getWinner($team1,$team2){
        if($team1<$team2){
            echo $team1.' minutes passed : '.$team2.' minutes passed';
            echo PHP_EOL.'Winner is Team1'.PHP_EOL;
        }
        elseif($team1>$team2){
            echo $team1.' minutes passed : '.$team2.' minutes passed';
            echo PHP_EOL.'Winner is Team2'.PHP_EOL;
        }
        else{
            echo $team1.' minutes passed : '.$team2.' minutes passed';
            echo PHP_EOL.'Draw'.PHP_EOL;
        }
    }
}