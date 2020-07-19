<?php
/**
 * @copyright Copyright (c) 2020 Jacob Siefer
 *
 * @see LICENSE
 */
declare(strict_types=1);

namespace Typesetsh\Pdf\Block;

use Magento\Sales;

class Shipment extends SalesLetter
{
    /**
     * @var Sales\Model\Order\Shipment
     */
    private $shipment;

    /**
     * @return Sales\Model\Order\Shipment
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getShipment(): Sales\Model\Order\Shipment
    {
        if (!$this->shipment) {
            $block = $this->getLayout()->getBlock('shipment');
            if ($block instanceof self) {
                return $block->getShipment();
            }
        }
        return $this->shipment;
    }

    /**
     * @param Sales\Model\Order\Shipment $shipment
     */
    public function setShipment(Sales\Model\Order\Shipment $shipment): void
    {
        $this->shipment = $shipment;
        $this->setOrder($shipment->getOrder());
        $this->setLetterAddress($shipment->getShippingAddress());
    }
}
