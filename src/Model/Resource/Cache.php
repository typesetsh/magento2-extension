<?php
/**
 * @copyright Copyright (c) 2020 Jacob Siefer
 *
 * @see LICENSE
 */
declare(strict_types=1);

namespace Typesetsh\Pdf\Model\Resource;

use Magento\Framework\App;
use Magento\Framework\Filesystem;
use typesetsh;

/**
 * Simple resource cache using Magentos var file system.
 */
class Cache
{
    /**
     * @var typesetsh\Resource\Cache
     */
    private $cache;

    /**
     * @param App\Filesystem\DirectoryList $dir
     * @param Filesystem $filesystem
     * @param string $dirName
     * @param int $downloadLimit
     *
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        App\Filesystem\DirectoryList $dir,
        Filesystem $filesystem,
        string $dirName = 'typesetsh',
        int $downloadLimit = 52428800
    ) {
        $cachePath = $filesystem->getDirectoryWrite($dir::CACHE);

        if (!$cachePath->isExist($dirName)) {
            $cachePath->create($dirName);
        }

        $cacheDir = $cachePath->getAbsolutePath($dirName);

        $this->cache = new typesetsh\Resource\Cache($cacheDir);
        $this->cache->downloadLimit = $downloadLimit;
    }

    /**
     * @param string $uri
     *
     * @return string
     */
    public function fetch(string $uri): string
    {
        return $this->cache->fetch($uri);
    }
}
