<?php
namespace Typesetsh\Pdf\Model\Sales;

use Magento\Framework\View;
use Magento\Sales;
use Typesetsh\Pdf;

class Creditmemo extends Sales\Model\Order\Pdf\Creditmemo
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
     * @param Sales\Model\Order\Creditmemo[] $creditmemos
     *
     * @return Pdf\Model\ZendPdf
     * @throws \Zend_Pdf_Exception
     */
    public function getPdf($creditmemos = [])
    {
        $htmlConverter = clone $this->htmlConverter;

        foreach ($creditmemos as $creditmemo) {
            $output = $this->render($creditmemo);
            $htmlConverter->append($output);
        }

        $pdf = $htmlConverter->render();

        return new Pdf\Model\ZendPdf($pdf);
    }

    protected function render(Sales\Model\Order\Creditmemo $creditmemo): string
    {
        $storeId = $creditmemo->getStoreId();

        return $this->storeRenderer->render($storeId, function () use ($creditmemo) {
            $resultPage = $this->resultPageFactory->create(true);
            $resultPage->addHandle('pdf_default');
            $resultPage->addHandle('pdf_sales_creditmemo');

            $block = $resultPage->getLayout()->getBlock('creditmemo');
            if ($block instanceof Pdf\Block\Creditmemo) {
                $block->setCreditmemo($creditmemo);
            }

            return $resultPage->renderPdf();
        });
    }
}
