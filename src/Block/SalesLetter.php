<?php

namespace Typesetsh\Pdf\Block;

use Magento\Directory;
use Magento\Framework\App;
use Magento\Framework\View;
use Magento\Payment;
use Magento\Sales;

class SalesLetter extends Letter
{
    /**
     * @var Payment\Helper\Data
     */
    private $paymentData;

    /**
     * @var Sales\Model\Order
     */
    private $order;

    public function __construct(
        View\Element\Template\Context $context,
        Sales\Model\Order\Address\Renderer $addressRenderer,
        App\Config\ScopeConfigInterface $scopeConfig,
        Directory\Model\CountryFactory $countryFactory,
        Payment\Helper\Data $paymentData,
        array $data = []
    ) {
        parent::__construct($context, $addressRenderer, $scopeConfig, $countryFactory, $data);
        $this->paymentData = $paymentData;
    }

    /**
     * @param string $method
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getPaymentLabel(string $method): string
    {
        return $this->paymentData->getMethodInstance($method)->getTitle();
    }

    /**
     * @return string
     */
    public function getInfoBlockHtml(): string
    {
        $payment = $this->getOrder()->getPayment();

        $paymentBlock = $this->paymentData->getInfoBlock($payment);
        $paymentBlock->getMethod()->setStore($this->getOrder()->getStoreId());

        return $paymentBlock->toHtml();
    }

    /**
     * @return Sales\Model\Order
     */
    public function getOrder(): Sales\Model\Order
    {
        return $this->order;
    }

    /**
     * @param Sales\Model\Order $order
     */
    public function setOrder(Sales\Model\Order $order): void
    {
        $this->order = $order;
    }
}
