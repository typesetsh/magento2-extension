<?php
namespace Typesetsh\Pdf\Block\Items;

use Magento\Sales;

class Shipment extends AbstractItems
{
    /**
     * @return Sales\Model\Order\Shipment
     */
    public function getShipment()
    {
        return $this->getParentBlock()->getShipment();
    }

    /**
     * @param   Sales\Model\Order\Shipment $shipment
     * @return  string
     */
    public function getShipmentTotalsHtml($shipment)
    {
        $html = '';
        $totals = $this->getChildBlock('shipment_totals');
        if ($totals) {
            $totals->setShipment($shipment);
            $html = $totals->toHtml();
        }
        return $html;
    }
}
