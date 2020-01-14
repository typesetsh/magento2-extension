<?php
namespace Typesetsh\Pdf\Block;

use Magento\Directory;
use Magento\Payment;
use Magento\Sales;

class Invoice extends SalesLetter
{
    /**
     * @var Sales\Model\Order\Invoice
     */
    private $invoice;

    /**
     * @return Sales\Model\Order\Invoice
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getInvoice(): \Magento\Sales\Model\Order\Invoice
    {
        if (!$this->invoice) {
            $block = $this->getLayout()->getBlock('invoice');
            if ($block instanceof self) {
                return $block->getInvoice();
            }
        }
        return $this->invoice;
    }

    /**
     * @param Sales\Model\Order\Invoice $invoice
     */
    public function setInvoice(Sales\Model\Order\Invoice $invoice): void
    {
        $this->invoice = $invoice;

        $this->setOrder($invoice->getOrder());
        $this->setLetterAddress($invoice->getBillingAddress());
    }
}
