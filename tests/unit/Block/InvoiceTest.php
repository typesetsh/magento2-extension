<?php

namespace Typesetsh\Pdf\Block;

use Typesetsh\Pdf;
use Magento\Framework;
use Magento\Sales;


/**
 * @covers \Typesetsh\Pdf\Block\Invoice
 */
class InvoiceTest extends Pdf\TestCase
{
    /** @test */
    public function retrieve_invoice_from_layout()
    {
        $order = $this->createOrder();

        $invoice = $this->getObject(\Magento\Sales\Model\Order\Invoice::class);
        $invoice->setOrder($order);


        $parentBlock = $this->getObject(Invoice::class);
        $parentBlock->setInvoice($invoice);

        $layout = $this->createMock(Framework\View\LayoutInterface::class);
        $layout->expects($this->exactly(1))
               ->method('getBlock')
               ->with('invoice')
               ->willReturn($parentBlock);


        $block = $this->getObject(Invoice::class, ['layout' => $layout]);
        $actual = $block->getInvoice();


        $this->assertSame($invoice, $actual, "Failed to retrieve invoice from \$parentBlock");
    }

    /** @test */
    public function setting_invoice_sets_shipping_address_as_letter_address()
    {
        $order = $this->createOrder();

        $addressRenderer = $this->createMock(Sales\Model\Order\Address\Renderer::class);
        $addressRenderer->method('format')->willReturn('foobar');

        $invoice = $this->getObject(Sales\Model\Order\Invoice::class);
        $invoice->setOrder($order);


        $addressBlock = $this->getObject(Address::class);


        $layout = $this->createMock(Framework\View\LayoutInterface::class);
        $layout->expects($this->exactly(1))
               ->method('getBlock')
               ->with('address')
               ->willReturn($addressBlock);

        $block = $this->getObject(
        Invoice::class,
            [
                'layout' => $layout,
                'addressRenderer' => $addressRenderer
            ]
        );

        $block->setInvoice($invoice);

        $this->assertSame('foobar', $addressBlock->getAddress());
    }

    /** @test */
    public function setting_invoice_sets_order_from_invoice()
    {
        $order = $this->createOrder();

        $invoice = $this->getObject(Sales\Model\Order\Invoice::class);
        $invoice->setOrder($order);


        $block = $this->getObject(Invoice::class);
        $block->setInvoice($invoice);


        $this->assertSame($order, $block->getOrder());
    }
}