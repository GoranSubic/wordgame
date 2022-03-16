<?php

namespace App\ddd\Application\DataTransformer\Game;

use App\ddd\Domain\Model\Game\Game;

class GameDtoDataTransformer implements GameDataTransformer
{
    private Game $game;

    public function write(Game $game)
    {
        $this->game = $game;

        return $this;
    }

    public function read()
    {
        return [
            'id' => $this->game->getId()->id(),
            'points' => $this->game->getPoints(),
            'existing' => $this->game->getIsExisting(),
        ];
    }
}
