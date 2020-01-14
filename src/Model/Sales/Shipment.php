<?php
namespace Typesetsh\Pdf\Model\Sales;

use Magento\Framework\View;
use Magento\Sales;
use Typesetsh\Pdf;

class Shipment extends Sales\Model\Order\Pdf\Shipment
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
     * @param Sales\Model\Order\Shipment[] $shipments
     *
     * @return Pdf\Model\ZendPdf
     * @throws \Zend_Pdf_Exception
     */
    public function getPdf($shipments = [])
    {
        $htmlConverter = clone $this->htmlConverter;

        foreach ($shipments as $shipment) {
            $output = $this->render($shipment);
            $htmlConverter->append($output);
        }

        $pdf = $htmlConverter->render();

        return new Pdf\Model\ZendPdf($pdf);
    }

    protected function render(Sales\Model\Order\Shipment $shipment): string
    {
        $storeId = $shipment->getStoreId();

        return $this->storeRenderer->render($storeId, function () use ($shipment) {
            $resultPage = $this->resultPageFactory->create(true);
            $resultPage->addHandle('pdf_default');
            $resultPage->addHandle('pdf_sales_shipment');

            $block = $resultPage->getLayout()->getBlock('shipment');
            if ($block instanceof Pdf\Block\Shipment) {
                $block->setShipment($shipment);
            }

            return $resultPage->renderPdf();
        });
    }
}
