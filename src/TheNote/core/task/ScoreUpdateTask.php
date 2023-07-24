<?php

namespace TheNote\core\task;

use pocketmine\scheduler\Task;
use TheNote\core\listener\ScoreBoardListner;

class ScoreUpdateTask extends Task {

    private $timer = 6;
    private $i = 0;
    public function onRun(): void {
        $sb = new ScoreBoardListner();
        $this->timer--;
        if ($this->timer >= 3) {
            if ($this->i == 2) {
                $sb->scoreboard();
            }
            if ($this->i == 5) {
                $this->i = 0;
            }
            $this->i++;
        }
    }
}
