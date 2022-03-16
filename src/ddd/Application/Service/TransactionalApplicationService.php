<?php

namespace App\ddd\Application\Service;

/**
 * Class TransactionalService
 * @package Ddd\Application\Service
 */
class TransactionalApplicationService implements ApplicationService
{
    /**
     * @var TransactionalSession
     */
    private TransactionalSession $session;

    /**
     * @var ApplicationService
     */
    private ApplicationService $service;

    /**
     * @param ApplicationService $service
     * @param TransactionalSession $session
     */
    public function __construct(ApplicationService $service, TransactionalSession $session)
    {
        $this->session = $session;
        $this->service = $service;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function execute($request = null): mixed
    {
        if (empty($this->service)) {
            throw new \LogicException('A use case must be specified');
        }

        $operation = function () use ($request) {
            return $this->service->execute($request);
        };

        return $this->session->executeAtomically($operation);
    }
}
