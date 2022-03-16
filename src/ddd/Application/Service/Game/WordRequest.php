<?php

namespace App\ddd\Application\Service\Game;

class WordRequest
{
    /**
     * @var string
     */
    private string $word;

    public function __construct(string $word)
    {
        $this->word = $word;
    }

    /**
     * @return string
     */
    public function getWord(): string
    {
        return $this->word;
    }

}