<?php

namespace App\ddd\Application\Service;

use App\ddd\Domain\Model\Word\Word;

interface DictionaryService
{
    /**
     * @param Word|null $word
     * @return bool
     */
    public function execute(Word $word = NULL): bool;
}