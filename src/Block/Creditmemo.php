<?php
/**
 * @copyright Copyright (c) 2020 Jacob Siefer
 *
 * @see LICENSE
 */
declare(strict_types=1);

namespace Typesetsh\Pdf\Block;

use Magento\Sales;

class Creditmemo extends SalesLetter
{
    /**
     * @var Sales\Model\Order\Creditmemo
     */
    private $creditmemo;

    /**
     * @return Sales\Model\Order\Creditmemo
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCreditmemo(): Sales\Model\Order\Creditmemo
    {
        if (!$this->creditmemo) {
            $block = $this->getLayout()->getBlock('creditmemo');
            if ($block instanceof self) {
                return $block->getCreditmemo();
            }
        }
        return $this->creditmemo;
    }

    /**
     * @param Sales\Model\Order\Creditmemo $creditmemo
     */
    public function setCreditmemo(Sales\Model\Order\Creditmemo $creditmemo): void
    {
        $this->creditmemo = $creditmemo;

        $this->setOrder($creditmemo->getOrder());
        $this->setLetterAddress($creditmemo->getBillingAddress());
    }
}
