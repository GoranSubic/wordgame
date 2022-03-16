<?php

namespace App\ddd\Application\DataTransformer\Game;

use App\ddd\Domain\Model\Game\Game;

interface GameDataTransformer
{
    /**
     * @param Game $object
     */
    public function write(Game $object);

    /**
     * @return mixed
     */
    public function read();
}
