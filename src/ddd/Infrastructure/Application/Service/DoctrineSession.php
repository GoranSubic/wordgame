<?php

namespace App\ddd\Infrastructure\Application\Service;

use App\ddd\Application\Service\TransactionalSession;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class DoctrineSession
 * @package Ddd\Infrastructure\Application\Service
 */
class DoctrineSession implements TransactionalSession
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function executeAtomically(callable $operation): mixed
    {
        return $this->entityManager->wrapInTransaction($operation);
    }
}
