<?php

namespace App\ddd\Domain\Model\Game;

use App\ddd\Domain\Model\Word\Word;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table(name="game")
 * @ORM\Entity(repositoryClass="App\ddd\Infrastructure\Domain\Model\Game\GameRepository")
 */
class Game
{
    const PALINDROME_POINTS = 3;
    const ALMOST_PALINDROME_POINTS = 2;

    /**
     * @var GameId
     * @ORM\Id()
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected GameId $gameId;

    /**
     * @var Word
     */
    protected Word $word;

    /**
     * Additional field - like surrogateWord
     *
     * @var string
     * @ORM\Column(name="word", type="string")
     */
    protected string $wordContent;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected int $points;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    protected DateTimeImmutable $createdOn;

    /**
     * @var GameStatus
     */
    protected GameStatus $isExisting;

    public function __construct(GameId $gameId = NULL, string $word = '', bool $emptyForm = FALSE)
    {
        $this->setGameId($gameId);
        if ($emptyForm) {
            $this->wordContent = '';
        } else {
            $this->setWord($word);
        }
        $this->points = 0;
        $this->createdOn = new DateTimeImmutable();
        $this->isExisting = GameStatus::Wrong;
    }

    /**
     * @return GameId
     */
    public function getId(): GameId
    {
        return $this->gameId;
    }

    private function setGameId($gameId)
    {
        if ($gameId === NULL) {
            $gameId = new GameId();
        }
        $this->gameId = $gameId;
    }


    /**
     * @return Word
     */
    public function getWord(): Word
    {
        if (NULL === $this->word) {
            $this->word = new Word(
                $this->wordContent
            );
        }

        return $this->word;
    }

    /**
     * @param string $word
     */
    protected function setWord(string $word = '')
    {
        $this->word = new Word($word);
        $this->wordContent = $this->word->getWord();
    }

    /**
     * @return string
     */
    public function getWordContent(): string
    {
        return $this->wordContent;
    }

    /**
     * @param string $wordContent
     */
    public function setWordContent(string $wordContent): void
    {
        $this->wordContent = $wordContent;
    }

    /**
     * @return int
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * @param int $points
     */
    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    /**
     * @return GameStatus
     */
    public function getIsExisting(): GameStatus
    {
        return $this->isExisting;
    }

    /**
     * @param GameStatus $isExisting
     */
    public function setIsExisting(GameStatus $isExisting): void
    {
        $this->isExisting = $isExisting;
    }

    public function calculatePoints(): void
    {
        $points = strlen(count_chars($this->wordContent, 3));
        $isPalindrome = $this->word->checkPalindrome();

        if ($isPalindrome) {
            $points += self::PALINDROME_POINTS;
        } else {
            $isAlmostPalindrome = $this->word->checkAlmostPalindrome();

            if ($isAlmostPalindrome) {
                $points += self::ALMOST_PALINDROME_POINTS;
            }
        }

        $this->points = $points;
    }

}
