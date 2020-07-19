<?php
namespace Typesetsh\Pdf\Model;

use typesetsh;

/**
 * Magento uses the Zend_Pdf library
 *
 * Instead of overwriting all controllers etc. we use a simple
 * wrapper for the Zend_Pdf and overwrite the render() function.
 */
class ZendPdf extends \Zend_Pdf
{
    /**
     * @var typesetsh\Result
     */
    private $result;

    /**
     * @param typesetsh\Result $result
     *
     * @throws \Zend_Pdf_Exception
     */
    public function __construct(typesetsh\Result $result)
    {
        parent::__construct();
        $this->result = $result;
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
        return $this->result->asString();
    }
}
