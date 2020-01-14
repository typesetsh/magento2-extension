<?php

namespace Typesetsh\Pdf\Block;

use Typesetsh\Pdf;
use Magento\Framework;
use Magento\Sales;


/**
 * @covers \Typesetsh\Pdf\Block\Creditmemo
 */
class CreditmemoTest extends Pdf\TestCase
{
    /** @test */
    public function retrieve_creditmemo_from_layout()
    {
        $order = $this->createOrder();

        $creditmemo = $this->getObject(\Magento\Sales\Model\Order\Creditmemo::class);
        $creditmemo->setOrder($order);


        $parentBlock = $this->getObject(Creditmemo::class);
        $parentBlock->setCreditmemo($creditmemo);

        $layout = $this->createMock(Framework\View\LayoutInterface::class);
        $layout->expects($this->exactly(1))
               ->method('getBlock')
               ->with('creditmemo')
               ->willReturn($parentBlock);


        $block = $this->getObject(Creditmemo::class, ['layout' => $layout]);
        $actual = $block->getCreditmemo();


        $this->assertSame($creditmemo, $actual, "Failed to retrieve creditmemo from \$parentBlock");
    }

    /** @test */
    public function setting_creditmemo_sets_shipping_address_as_letter_address()
    {
        $order = $this->createOrder();

        $addressRenderer = $this->createMock(Sales\Model\Order\Address\Renderer::class);
        $addressRenderer->method('format')->willReturn('foobar');

        $creditmemo = $this->getObject(Sales\Model\Order\Creditmemo::class);
        $creditmemo->setOrder($order);


        $addressBlock = $this->getObject(Address::class);


        $layout = $this->createMock(Framework\View\LayoutInterface::class);
        $layout->expects($this->exactly(1))
               ->method('getBlock')
               ->with('address')
               ->willReturn($addressBlock);

        $block = $this->getObject(
        Creditmemo::class,
            [
                'layout' => $layout,
                'addressRenderer' => $addressRenderer
            ]
        );

        $block->setCreditmemo($creditmemo);

        $this->assertSame('foobar', $addressBlock->getAddress());
    }

    /** @test */
    public function setting_creditmemo_sets_order_from_creditmemo()
    {
        $order = $this->createOrder();

        $creditmemo = $this->getObject(Sales\Model\Order\Creditmemo::class);
        $creditmemo->setOrder($order);


        $block = $this->getObject(Creditmemo::class);
        $block->setCreditmemo($creditmemo);


        $this->assertSame($order, $block->getOrder());
    }
}