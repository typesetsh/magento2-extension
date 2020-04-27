<?php
namespace Typesetsh\Pdf\Model\Sales;

use jsiefer\Css;
use jsiefer\IO;
use jsiefer\Pdf;
use Typesetsh\Pdf\Model;
use typesetsh\Html;

class HtmlConverter
{
    /**
     * @var Html\Parser
     */
    private $parser;

    /**
     * @var Css\Engine\LayoutEngine
     */
    private $layoutEngine;

    /**
     * @var Pdf\Graphic\ContentStream
     */
    private $contentWriter;

    /**
     * @var Css\Renderer\Pdf
     */
    private $renderer;

    /**
     * @var Css\Engine\Box[]
     */
    private $pages = [];

    /**
     * @var \Closure
     */
    private $resolveUri;

    /**
     * @var Model\Resource\Cache
     */
    private $cache;

    /**
     * @param Html\Parser|null $parser
     * @param Pdf\Graphic\ContentStream $contentWriter
     * @param Css\Renderer\Pdf $renderer
     * @param Css\Engine\LayoutEngine $layoutEngine
     * @param Model\Resource\Cache $cache
     */
    public function __construct(
        Html\Parser $parser,
        Pdf\Graphic\ContentStream $contentWriter,
        Css\Renderer\Pdf $renderer,
        Css\Engine\LayoutEngine $layoutEngine,
        Model\Resource\Cache $cache
    ) {
        $this->parser = $parser;
        $this->contentWriter = $contentWriter;
        $this->layoutEngine = $layoutEngine;
        $this->renderer = $renderer;
        $this->cache = $cache;

        $this->resolveUri = $this->setupUriResolver();
    }

    /**
     * @param string $html
     *
     * @return \typesetsh\Document
     */
    public function append(string $html)
    {
        $document = $this->parser->parse($html, $this->resolveUri);

        $documentBox = $this->layoutEngine->compose($document->dom, $document->stylesheet);
        array_push($this->pages, ...$documentBox->children);

        return $document;
    }

    /**
     * @return Pdf\Document
     */
    public function render(): Pdf\Document
    {
        $pages = new Pdf\Pages();
        foreach ($this->pages as $pageBox) {
            $page = Pdf\Page::create($pageBox->width, $pageBox->height);
            $pages->Kids[] = $page;

            $operations = [];
            $this->renderer->render($pageBox, $operations);

            $output = $this->contentWriter->write($page->Resources, $operations, $page);
            $output = new IO\Memory($output);

            $page->Contents->data($output);
        }

        $document = new Pdf\Document();
        $document->Catalog = new Pdf\Catalog();
        $document->Catalog->Pages = $pages;

        return $document;
    }

    /**
     * @return \Closure
     */
    protected function setupUriResolver(): \Closure
    {
        return function (string $uri, ?string $baseUri = '') {
            if ($uri[0] === '/') {
                $uri = $baseUri . $uri;
            }
            if (strpos($uri, 'https://') === 0 || strpos($uri, 'http://') === 0) {
                return $this->cache->fetch($uri);
            }

            return '';
        };
    }
}
