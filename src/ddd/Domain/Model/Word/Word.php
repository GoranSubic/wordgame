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
     * Checks word equality.
     * 
     * @return bool
     */
    public function equals(Word $word): bool
    {
        return $word->getWord() === $this->word;
    }

    /**
     * Check if word is AlmostPalindrome.
     * 
     * @return bool
     */
    public function checkAlmostPalindrome(): bool
    {
        for ($i = 0; $i < strlen($this->word); $i++) {
            $wordToCheck = substr_replace($this->word, '', $i, 1);

            if ($this->checkPalindrome($wordToCheck)) {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * Check if word is palindrome.
     * 
     * @param string $recursionWord
     * @return bool
     */
    public function checkPalindrome(string $recursionWord = ''): bool
    {
        $wordToCheck = $recursionWord ?: $this->word;

        if ((strlen($wordToCheck) == 1) || (strlen($wordToCheck) == 0)){
            return TRUE;
        } else {
            if (substr($wordToCheck, 0, 1) == substr($wordToCheck, (strlen($wordToCheck) - 1), 1)) {
                $wordLen = strlen(substr($wordToCheck, 1, strlen($wordToCheck) - 2));
                if ($wordLen == 1 || $wordLen == 0) {
                    return TRUE;
                }

                return $this->checkPalindrome(substr($wordToCheck, 1, strlen($wordToCheck) - 2));
            } else {
                return FALSE;
            }
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->word;
    }
}