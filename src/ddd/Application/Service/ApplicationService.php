<?php

namespace App\ddd\Application\Service;

interface ApplicationService
{
    /**
     * @param null $request
     * @return mixed
     */
    public function execute($request = NULL): mixed;
}