<?php

namespace App\ddd\Application\Service\Game;

use App\ddd\Application\DataTransformer\Game\GameDataTransformer;
use App\ddd\Application\Service\ApplicationService;
use App\ddd\Application\Service\DictionaryService;
use App\ddd\Domain\Model\Game\Game;
use App\ddd\Domain\Model\Game\GameId;
use App\ddd\Domain\Model\Game\GameRepository;
use App\ddd\Domain\Model\Game\GameStatus;

class GameService implements ApplicationService
{
    private GameRepository $gameRepository;
    private GameDataTransformer $gameDataTransformer;
    private DictionaryService $dictionaryService;

    public function __construct(
        GameRepository $gameRepository,
        GameDataTransformer $gameDataTransformer,
        DictionaryService $dictionaryService
    )
    {
        $this->gameRepository = $gameRepository;
        $this->gameDataTransformer = $gameDataTransformer;
        $this->dictionaryService = $dictionaryService;
    }

    /**
     * @param WordRequest|null $wordToCheck
     * @return mixed
     */
    public function execute($wordToCheck = NULL): mixed
    {
        $questWord = $wordToCheck->getWord();

        $existingWord = $this->gameRepository->findByWord($questWord);
//        $existingGame = $this->gameRepository->ofWord($questWord);

        if (empty($existingWord) && !empty($questWord)) {
            // If word is not in DB, create new game and check if palindrome.
            $game = new Game(
                $this->gameRepository->nextIdentity(),
                $questWord
            );

            if ($this->dictionaryService->execute($game->getWord())) {
                // If it is english word then calculate word points.
                $game->calculatePoints();

                // Save game to DB.
                $this->gameRepository->add($game);

                $game->setIsExisting(GameStatus::New);
            }
        } else {
            // If word is in DB create "existing" game (with existing ID)???
            $game = new Game(
                new GameId($existingWord[0]['gameId']),
                $questWord
            );
            $game->calculatePoints();
            $game->setIsExisting(GameStatus::Existing);
        }

        $this->gameDataTransformer->write($game);
        return $this->gameDataTransformer->read();
    }
}
