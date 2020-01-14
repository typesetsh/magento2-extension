<?php
namespace Typesetsh\Pdf;

use PHPUnit;
use Magento\Framework;
use Magento\Sales;


abstract class TestCase extends PHPUnit\Framework\TestCase
{
    /** @var Framework\TestFramework\Unit\Helper\ObjectManager */
    protected $objectManager;

    protected function setUp(): void
    {
        $this->objectManager = new Framework\TestFramework\Unit\Helper\ObjectManager($this);
    }

    protected function createOrder(): Sales\Model\Order
    {
        $shippingAddress = $this->createOrderAddress(Sales\Model\Order\Address::TYPE_SHIPPING);
        $billingAddress = $this->createOrderAddress(Sales\Model\Order\Address::TYPE_BILLING);

        /** @var Sales\Model\Order $order */
        $order = $this->getObject(Sales\Model\Order::class);
        $order->setData('addresses', [$billingAddress, $shippingAddress]);

        return $order;
    }

    protected function createOrderAddress(string $type = ''): Sales\Model\Order\Address
    {
        /** @var Sales\Model\Order\Address $address */
        $address = $this->getObject(Sales\Model\Order\Address::class);
        $address->setAddressType($type);

        return $address;
    }

    protected function getObject(string $className, array $arguments = []): object
    {
        return $this->objectManager->getObject($className, $arguments);
    }
}
