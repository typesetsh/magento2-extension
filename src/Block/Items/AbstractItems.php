<?php
/**
 * @copyright Copyright (c) 2020 Jacob Siefer
 *
 * @see LICENSE
 */
declare(strict_types=1);

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
