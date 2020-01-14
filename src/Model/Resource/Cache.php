<?php
namespace Typesetsh\Pdf\Model\Resource;

use typesetsh;
use Magento\Framework\App;
use Magento\Framework\Filesystem;

class Cache extends typesetsh\Resource\Cache
{
    /**
     * @var App\State
     */
    private $appState;

    /**
     * @param App\Filesystem\DirectoryList $dir
     * @param App\State $appState
     * @param Filesystem $filesystem
     * @param string $dirName
     * @param int $downloadLimit
     *
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        App\Filesystem\DirectoryList $dir,
        App\State $appState,
        Filesystem $filesystem,
        string $dirName = 'typesetsh',
        int $downloadLimit = 52428800
    ) {
        $this->appState = $appState;
        $this->downloadLimit = $downloadLimit;

        $cachePath = $filesystem->getDirectoryWrite($dir::CACHE);

        if (!$cachePath->isExist($dirName)) {
            $cachePath->create($dirName);
        }

        $cacheDir = $cachePath->getAbsolutePath($dirName);

        parent::__construct($cacheDir);
    }

    /**
     * @param string $uri
     *
     * @return string
     * @throws \Exception
     */
    public function fetch(string $uri): string
    {
        if ($this->appState->getMode() === $this->appState::MODE_DEVELOPER) {
            $uri .= '#' . \random_int(0, 10000000);
        }

        return parent::fetch($uri);
    }
}
