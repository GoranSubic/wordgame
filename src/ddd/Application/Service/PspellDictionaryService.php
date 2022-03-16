<?php

namespace App\ddd\Application\Service;

use App\ddd\Domain\Model\Word\Word;

class PspellDictionaryService implements DictionaryService
{
    private $pspell;

    /**
     * Load a new en dictionary.
     */
    public function __construct()
    {
        $this->pspell = pspell_new('en');
    }

    /**
     * Return if param word is english word or not.
     *
     * @param Word|null $word
     * @return bool
     */
    public function execute(Word $word = NULL): bool
    {
        $requestWord = $word->getWord();

        return pspell_check($this->pspell, $requestWord);
    }

}