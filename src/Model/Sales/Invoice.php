<?php
/**
 * @copyright Copyright (c) 2020 Jacob Siefer
 *
 * @see LICENSE
 */
declare(strict_types=1);

namespace Typesetsh\Pdf\Model\Sales;

use Magento\Framework\View;
use Magento\Sales;
use Typesetsh\Pdf;

class Invoice extends Sales\Model\Order\Pdf\Invoice
{
    /**
     * @var View\Result\PageFactory
     */
    private $resultPageFactory;

    /**
     * @var HtmlConverter
     */
    private $htmlConverter;

    /**
     * @var Pdf\Model\StoreRenderer
     */
    private $storeRenderer;

    public function __construct(
        View\Result\PageFactory $resultPageFactory,
        HtmlConverter $htmlConverter,
        Pdf\Model\StoreRenderer $storeRenderer
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->htmlConverter = $htmlConverter;
        $this->storeRenderer = $storeRenderer;
    }

    /**
     * @param Sales\Model\Order\Invoice[] $invoices
     *
     * @return Pdf\Model\ZendPdf
     * @throws \Zend_Pdf_Exception
     */
    public function getPdf($invoices = [])
    {
        $htmlConverter = clone $this->htmlConverter;

        foreach ($invoices as $invoice) {
            $output = $this->render($invoice);
            $htmlConverter->append($output);
        }

        $pdf = $htmlConverter->render();

        return new Pdf\Model\ZendPdf($pdf);
    }

    protected function render(Sales\Model\Order\Invoice $invoice): string
    {
        $storeId = $invoice->getStoreId();

        return $this->storeRenderer->render($storeId, function () use ($invoice) {
            $resultPage = $this->resultPageFactory->create(true);
            $resultPage->addHandle('pdf_default');
            $resultPage->addHandle('pdf_sales_invoice');

            assert($resultPage instanceof Pdf\Model\Result\Page);

            $block = $resultPage->getLayout()->getBlock('invoice');
            if ($block instanceof Pdf\Block\Invoice) {
                $block->setInvoice($invoice);
            }

            return $resultPage->renderPdf();
        });
    }
}
