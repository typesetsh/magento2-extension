<?php
namespace Typesetsh\Pdf\Block\Items;

use Magento\Sales;

class Creditmemo extends AbstractItems
{
    /**
     * @return Sales\Model\Order\Invoice
     */
    public function getCreditmemo()
    {
        return $this->getParentBlock()->getCreditmemo();
    }

    /**
     * @param   Sales\Model\Order\Creditmemo $creditmemo
     *
     * @return  string
     */
    public function getCreditmemoTotalsHtml($creditmemo)
    {
        $html = '';
        $totals = $this->getChildBlock('creditmemo_totals');
        if ($totals) {
            $totals->setCreditmemo($creditmemo);
            $html = $totals->toHtml();
        }
        return $html;
    }

    /**
     * Get html of creditmemo comments block
     *
     * @param   \Magento\Sales\Model\Order\Creditmemo $creditmemo
     * @return  string
     */
    public function getCommentsHtml($creditmemo)
    {
        $html = '';
        $comments = $this->getChildBlock('creditmemo_comments');
        if ($comments) {
            $comments->setEntity($creditmemo)->setTitle(__('About Your Refund'));
            $html = $comments->toHtml();
        }
        return $html;
    }
}
