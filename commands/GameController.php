<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\game\Parser;
use app\game\Team;
use app\game\Game;
use yii\helpers\Console;
/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GameController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($team1,$team2)
    {

        $parserObj = new Parser;
        $team1 = $parserObj->teamParse($team1);
        $team2 =  $parserObj->teamParse($team2);
        $teamObj1 = new Team();
        $teamObj2 = new Team();
        $gameObj = new Game;
        

        echo $this->ansiFormat('Game Results', Console::FG_RED, Console::UNDERLINE).PHP_EOL;

        echo $gameObj->getWinner($gameObj->getTeamScore($teamObj1->getTeamDetails($team1),0),$gameObj->getTeamScore($teamObj2->getTeamDetails($team2),0));
        return ExitCode::OK;
    }
}
