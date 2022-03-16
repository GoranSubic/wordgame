<?php

namespace App\ddd\Domain\Model\Game;

enum GameStatus
{
    case New;
    case Existing;
    case Wrong;
}