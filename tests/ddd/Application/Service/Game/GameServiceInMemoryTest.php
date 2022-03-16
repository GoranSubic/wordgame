<?php

namespace App\Tests\ddd\Application\Service\Game;

use App\ddd\Application\DataTransformer\Game\GameDataTransformer;
use App\ddd\Application\DataTransformer\Game\GameDtoDataTransformer;
use App\ddd\Application\Service\ApplicationService;
use App\ddd\Application\Service\DictionaryService;
use App\ddd\Application\Service\Game\GameService;
use App\ddd\Application\Service\Game\WordRequest;
use App\ddd\Application\Service\PspellDictionaryService;
use App\ddd\Domain\Model\Game\GameId;
use App\ddd\Domain\Model\Game\GameRepository;
use App\ddd\Domain\Model\Game\GameStatus;
use App\ddd\Infrastructure\Persistence\InMemory\Game\InMemoryGameRepository;
use PHPUnit\Framework\TestCase;

class GameServiceInMemoryTest extends TestCase
{
    private GameRepository $gameRepository;
    private GameDataTransformer $gameDataTransformer;
    private DictionaryService $dictionaryService;
    private ApplicationService $gameService;

    public function setUp(): void
    {
        $this->gameRepository = new InMemoryGameRepository();
        $this->gameDataTransformer = new GameDtoDataTransformer();
        $this->dictionaryService = new PspellDictionaryService();

        $this->gameService = new GameService(
            $this->gameRepository,
            $this->gameDataTransformer,
            $this->dictionaryService
        );
    }

    private function executeCheckWord($word)
    {
        $request = new WordRequest($word);
        return $this->gameService->execute($request);
    }

    public function testAfterCheckWordItShouldBeInTheRepository()
    {
        $game = $this->executeCheckWord('test');
        $gameId = new GameId($game['id']);

        $this->assertNotNull(
            $this->gameRepository->ofId($gameId)
        );
    }

    public function testSameWordShouldHaveStatusExisting()
    {
        $game1 = $this->executeCheckWord('test');
        $game2 = $this->executeCheckWord('test');

        $this->assertEquals(
            GameStatus::New,
            $game1['existing']
        );

        $this->assertEquals(
            GameStatus::Existing,
            $game2['existing']
        );
    }

    /**
     * @param $word
     * @param $expectedPoints
     * @dataProvider calculatedWords
     */
    public function testCalculatePoints($word, $expectedPoints): void
    {
        $game = $this->executeCheckWord($word);

        $this->assertEquals($expectedPoints, $game['points']);
    }

    public function calculatedWords(): array
    {
        return [
            [' test word ', 5],
            [' rotor  ', 6],
            ['a', 4],
            ['aaa', 0],
        ];
    }
}
