<?php

namespace App\ddd\Application\Service;

interface CacheInterface {
  public function deleteCacheItem(string $item);
}