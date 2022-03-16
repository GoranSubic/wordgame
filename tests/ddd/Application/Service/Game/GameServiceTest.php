<?php

namespace App\Tests\ddd\Application\Service\Game;

use App\ddd\Application\DataTransformer\Game\GameDtoDataTransformer;
use App\ddd\Application\Service\ApplicationService;
use App\ddd\Application\Service\Game\GameService;
use App\ddd\Application\Service\Game\WordRequest;
use App\ddd\Application\Service\PspellDictionaryService;
use App\ddd\Domain\Model\Game\GameId;
use App\ddd\Domain\Model\Game\GameRepository;
use App\ddd\Domain\Model\Game\GameStatus;
use App\ddd\Infrastructure\Domain\Model\Game\GameRepository as InfraGameRepository;
use PHPUnit\Framework\TestCase;

class GameServiceTest extends TestCase
{
    private GameRepository $gameRepositoryMock;
    private ApplicationService $gameService;

    public function setUp(): void
    {
        $this->gameRepositoryMock = $this->createMock(InfraGameRepository::class);
        $gameDataTransformer = new GameDtoDataTransformer();
        $dictionaryService = new PspellDictionaryService();

        $this->gameService = new GameService(
            $this->gameRepositoryMock,
            $gameDataTransformer,
            $dictionaryService
        );
    }

    private function executeCheckWord($word)
    {
        $request = new WordRequest($word);
        return $this->gameService->execute($request);
    }

    public function testAfterCheckWordItShouldBeInTheRepository()
    {
        $this->gameRepositoryMock->method('nextIdentity')
            ->willReturn(new GameId());

        $request = new WordRequest('test');
        $game = $this->gameService->execute($request);

        $gameId = new GameId($game['id']);

        $this->assertNotNull(
            $this->gameRepositoryMock->ofId($gameId)
        );
    }

    public function testSameWordShouldHaveStatusExisting()
    {
        $game1 = $this->executeCheckWord('test');
        $this->assertEquals(
            GameStatus::New,
            $game1['existing']
        );

        $this->gameRepositoryMock->method('findByWord')
            ->willReturn([0 => ['gameId' => new GameId()]]);
        $game2 = $this->executeCheckWord('test');
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

    private function calculatedWords(): array
    {
        return [
            [' test word ', 5],
            [' rotor  ', 6],
            ['a', 4],
            ['aaa', 0],
        ];
    }
}