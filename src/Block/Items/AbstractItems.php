<?php
namespace Typesetsh\Pdf\Block\Items;

use Magento\Sales;


class AbstractItems extends Sales\Block\Items\AbstractItems
{
    /**
     * @return Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }
}
