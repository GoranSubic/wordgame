<?php

namespace App\ddd\Domain\Model\Word;

class Word
{
    private string $word;

    /**
     * @param string $word
     */
    public function __construct(string $word = '')
    {
        $this->setWord($word);
    }

    /**
     * @return string
     */
    public function getWord(): string
    {
        return $this->word;
    }

    /**
     * @param string $word
     * @return $this
     */
    public function setWord(string $word): self
    {
        $word = trim($word);
        $wordArr = explode(' ', $word);
        $word = $wordArr[0];

        if (!$word) {
            throw new \InvalidArgumentException('Word cannot be empty');
        }

        $this->word = $word;

        return $this;
    }



    /**
     * @return string
     */
    public function __toString()
    {
        return $this->word;
    }
}