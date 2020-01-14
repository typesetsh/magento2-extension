<?php

namespace Typesetsh\Pdf\Block;

use Typesetsh\Pdf;
use Magento\Framework;
use Magento\Sales;


/**
 * @covers \Typesetsh\Pdf\Block\Shipment
 */
class ShipmentTest extends Pdf\TestCase
{
    /** @test */
    public function retrieve_shipment_from_layout()
    {
        $order = $this->createOrder();

        $shipment = $this->getObject(\Magento\Sales\Model\Order\Shipment::class);
        $shipment->setOrder($order);


        $parentBlock = $this->getObject(Shipment::class);
        $parentBlock->setShipment($shipment);

        $layout = $this->createMock(Framework\View\LayoutInterface::class);
        $layout->expects($this->exactly(1))
               ->method('getBlock')
               ->with('shipment')
               ->willReturn($parentBlock);


        $block = $this->getObject(Shipment::class, ['layout' => $layout]);
        $actual = $block->getShipment();


        $this->assertSame($shipment, $actual, "Failed to retrieve shipment from \$parentBlock");
    }

    /** @test */
    public function setting_shipment_sets_shipping_address_as_letter_address()
    {
        $order = $this->createOrder();

        $addressRenderer = $this->createMock(Sales\Model\Order\Address\Renderer::class);
        $addressRenderer->method('format')->willReturn('foobar');

        $shipment = $this->getObject(Sales\Model\Order\Shipment::class);
        $shipment->setOrder($order);


        $addressBlock = $this->getObject(Address::class);


        $layout = $this->createMock(Framework\View\LayoutInterface::class);
        $layout->expects($this->exactly(1))
               ->method('getBlock')
               ->with('address')
               ->willReturn($addressBlock);

        $block = $this->getObject(
        Shipment::class,
            [
                'layout' => $layout,
                'addressRenderer' => $addressRenderer
            ]
        );

        $block->setShipment($shipment);

        $this->assertSame('foobar', $addressBlock->getAddress());
    }

    /** @test */
    public function setting_shipment_sets_order_from_shipment()
    {
        $order = $this->createOrder();

        $shipment = $this->getObject(Sales\Model\Order\Shipment::class);
        $shipment->setOrder($order);


        $block = $this->getObject(Shipment::class);
        $block->setShipment($shipment);


        $this->assertSame($order, $block->getOrder());
    }
}