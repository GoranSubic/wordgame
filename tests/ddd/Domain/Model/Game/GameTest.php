<?php

namespace App\Tests\Ddd\Domain\Model\Game;

use App\ddd\Domain\Model\Game\Game;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    private function createGame($word): Game
    {
        return new Game(NULL, $word);
    }

    public function testEmptyWordShouldThrowAnException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->createGame('');
    }

    public function testShouldSanitizeWord(): void
    {
        $game = $this->createGame('  test word  ');
        $this->assertEquals('test', $game->getWord()->getWord());
    }

    /**
     * @param $word
     * @param $expectedWord
     * @dataProvider sanitizedWords
     */
    public function testShouldSanitizeWords($word, $expectedWord): void
    {
        $game = $this->createGame($word);
        $this->assertEquals($expectedWord, $game->getWord()->getWord());
    }

    public function sanitizedWords(): array
    {
        return [
            ['test word', 'test'],
            ['  test word  ', 'test'],
            ['  test word', 'test'],
        ];
    }

}
