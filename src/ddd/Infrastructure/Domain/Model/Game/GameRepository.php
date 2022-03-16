<?php

namespace App\ddd\Infrastructure\Domain\Model\Game;

use App\ddd\Domain\Model\Game\Game;
use App\ddd\Domain\Model\Game\GameId;
use \App\ddd\Domain\Model\Game\GameRepository as DomainGameRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository implements DomainGameRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function findByWord(string $value): array
    {
        $qb = $this->createQueryBuilder('g');

        $qb
            ->select('g.gameId', 'g.points')
            ->where('g.wordContent = :val')
            ->setParameter('val', $value)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
            ;

        $query = $qb->getQuery();

        return $query->execute();
    }

    /**
     * @param GameId $gameId
     * @return Game
     */
    public function ofId(GameId $gameId): Game
    {
        return $this->find($gameId);
    }

    /**
     * @param string $word
     * @return Game
     */
    public function ofWord(string $word): Game
    {
        return $this->findOneBy(['wordContent' => $word]);
    }

    /**
     * @param Game $game
     */
    public function add(Game $game)
    {
        $this->getEntityManager()->persist($game);
    }

    /**
     * @return GameId
     */
    public function nextIdentity(): GameId
    {
        return new GameId();
    }


    // /**
    //  * @return GameEntity[] Returns an array of GameEntity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GameEntity
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
