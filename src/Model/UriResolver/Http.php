<?php
/**
 * @copyright Copyright (c) 2020 Jacob Siefer
 *
 * @see LICENSE
 */
declare(strict_types=1);

namespace Typesetsh\Pdf\Model\UriResolver;

use typesetsh;
use Typesetsh\Pdf\Model\Resource\Cache;
use Magento\Framework\App;

class Http implements typesetsh\UriResolver\Scheme
{
    /**
     * @var App\State
     */
    private $appState;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @param App\State $appState
     * @param Cache $cache
     */
    public function __construct(
        App\State $appState,
        Cache $cache
    ) {
        $this->appState = $appState;
        $this->cache = $cache;
    }

    public function resolve(string $uri): string
    {
        if ($this->appState->getMode() === $this->appState::MODE_DEVELOPER) {
            $uri .= '#' . \random_int(0, 10000000);
        }

        return $this->cache->fetch($uri);
    }
}
