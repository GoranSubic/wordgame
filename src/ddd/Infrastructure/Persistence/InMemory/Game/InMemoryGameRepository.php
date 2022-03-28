<?php

namespace App\ddd\Infrastructure\Persistence\InMemory\Game;

use App\ddd\Domain\Model\Game\Game;
use App\ddd\Domain\Model\Game\GameId;
use App\ddd\Domain\Model\Game\GameRepository;

class InMemoryGameRepository implements GameRepository
{
    /**
     * @var array
     */
    private array $games = [];

    public function findByWord(string $word): array
    {
        $return = [];
        foreach ($this->games as $game) {
            if ($game->getWordContent() === $word) {
                return $return = [
                    '0' => [
                            'gameId' => $game->getId()->id(),
                            'points' => $game->getPoints(),
                        ]
                    ];
            }
        }
        return $return;
    }

    public function findAllWords(): array
    {
        $return = [];
        foreach ($this->games as $game) {
            $return = [
                'gameId' => $game->getId()->id(),
                'wordContent' => $game->getWordContent(),
                'points' => $game->getPoints(),
            ];
        }
        return $return;
    }

    /**
     * @param GameId $gameId
     * @return Game
     */
    public function ofId(GameId $gameId): Game
    {
//        if (!isset($this->games[$gameId->id()])) {
//            return;
//        };

        return $this->games[$gameId->id()];
    }

    /**
     * @param string $word
     * @return Game
     */
    public function ofWord(string $word): Game
    {
        foreach ($this->games as $game) {
            if ($game->getWordContent() === $word) {
                return $game;
            }
        }
//        return;
    }

    /**
     * @param Game $game
     */
    public function add(Game $game)
    {
        $this->games[$game->getId()->id()] = $game;
    }

    /**
     * @return GameId
     */
    public function nextIdentity(): GameId
    {
        return new GameId();
    }
}