<?php
/**
 * @copyright Copyright (c) 2020 Jacob Siefer
 *
 * @see LICENSE
 */
declare(strict_types=1);

namespace Typesetsh\Pdf\Model\Sales;

use typesetsh;
use typesetsh\Document;
use typesetsh\Html;
use typesetsh\Pdf;
use Typesetsh\Pdf\Model;

class HtmlConverter
{
    /**
     * @var Html\Parser
     */
    private $parser;

    /**
     * @var Pdf\Renderer
     */
    private $renderer;

    /**
     * @var Document[]
     */
    private $documents = [];

    /**
     * @var callable
     */
    private $resolveUri;

    /**
     * @param Html\Parser $parser
     * @param Pdf\Renderer $renderer
     * @param Model\UriResolver\Http[] $schemeResolvers
     */
    public function __construct(
        Html\Parser $parser,
        Pdf\Renderer $renderer,
        array $schemeResolvers
    ) {
        $this->parser = $parser;
        $this->renderer = $renderer;

        $this->resolveUri = new typesetsh\UriResolver($schemeResolvers);
    }
    /**
     * @param string $html
     *
     * @return Document
     */
    public function append(string $html)
    {
        return $this->documents[] = $this->parser->parse($html, $this->resolveUri);
    }

    /**
     * @return typesetsh\Result
     */
    public function render(): typesetsh\Result
    {
        if (!$this->documents) {
            throw new \RuntimeException("No content added. See append()");
        }

        return $this->renderer->run($this->documents, 1000);
    }
}
