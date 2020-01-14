<?php

namespace Typesetsh\Pdf\Model;

use jsiefer\IO;
use jsiefer\Pdf;

/**
 * Magento uses the Zend_Pdf library
 *
 * Instead of overwriting all controllers etc. use a simple
 * wrapper for the Zend and provide the render() function.
 */
class ZendPdf extends \Zend_Pdf
{
    /**
     * @var Pdf\Document
     */
    private $document;

    /**
     * @param Pdf\Document $document
     *
     * @throws \Zend_Pdf_Exception
     */
    public function __construct(Pdf\Document $document)
    {
        parent::__construct();
        $this->document = $document;
    }

    /**
     * @param bool $newSegmentOnly
     * @param resource|null $outputStream
     *
     * @return string
     * @throws \Exception
     */
    public function render($newSegmentOnly = false, $outputStream = null)
    {
        $saveHandler = new Pdf\SaveHandler\TableSaveHandler();
        if ($outputStream) {
            throw new \Exception("Output stream not supported");
        }

        $output = IO\string();
        $saveHandler->save($this->document, $output);

        return $output->rewind()->read();
    }
}
