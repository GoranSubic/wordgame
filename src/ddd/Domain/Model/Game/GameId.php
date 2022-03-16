<?php

namespace App\ddd\Domain\Model\Game;

use Ramsey\Uuid\Uuid;

class GameId
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @param string|null $id
     */
    public function __construct(string $id = NULL)
    {
        $this->id = $id ?: Uuid::uuid4()->toString();
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param GameId $gameId
     *
     * @return bool
     */
    public function equals(GameId $gameId): bool
    {
        return $this->id() === $gameId->id();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->id();
    }
}