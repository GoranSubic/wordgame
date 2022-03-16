<?php

namespace App\ddd\Domain\Model\Game;

use App\ddd\Infrastructure\Domain\Model\Game\DoctrineGame;

/**
 * Interface GameRepository
 */
interface GameRepository
{
    /**
     * @param GameId $gameId
     *
     * @return Game
     */
    public function ofId(GameId $gameId): Game;

    /**
     * @param string $word
     *
     * @return Game
     */
    public function ofWord(string $word): Game;

    /**
     * @param string $word
     *
     * @return array
     */
    public function findByWord(string $word): array;

    /**
     * @param Game $game
     */
    public function add(Game $game);

    /**
     * @return GameId
     */
    public function nextIdentity(): GameId;

}