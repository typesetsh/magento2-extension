<?php
namespace Typesetsh\Pdf\Block\Items;

use Magento\Sales;

class Invoice extends AbstractItems
{
    /**
     * @return Sales\Model\Order\Invoice
     */
    public function getInvoice()
    {
        return $this->getParentBlock()->getInvoice();
    }

    /**
     * Get html of invoice totals block
     *
     * @param   Sales\Model\Order\Invoice $invoice
     * @return  string
     */
    public function getInvoiceTotalsHtml($invoice)
    {
        $html = '';
        $totals = $this->getChildBlock('invoice_totals');
        if ($totals) {
            $totals->setInvoice($invoice);
            $html = $totals->toHtml();
        }
        return $html;
    }
}
