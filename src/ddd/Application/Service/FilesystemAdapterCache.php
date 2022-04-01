<?php

namespace App\ddd\Application\Service;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class FilesystemAdapterCache implements CacheInterface
{
  /**
   * @var FilesystemAdapter $cache
   */
  public $cache;

  public function __construct()
  {
    $this->cache = new FilesystemAdapter();
  }

  public function deleteCacheItem(string $item)
  {
    $this->cache->deleteItem($item);
  }
}