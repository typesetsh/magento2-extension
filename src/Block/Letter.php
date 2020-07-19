<?php
/**
 * @copyright Copyright (c) 2020 Jacob Siefer
 *
 * @see LICENSE
 */
declare(strict_types=1);

namespace Typesetsh\Pdf\Block;

use Magento\Directory;
use Magento\Framework\App;
use Magento\Framework\View;
use Magento\Sales;
use Magento\Store;

/**
 * @suppress PhanUndeclaredTypeProperty, PhanUndeclaredClassMethod
 */
class Letter extends View\Element\Template
{
    /**
     * @var Sales\Model\Order\Address\Renderer
     */
    protected $addressRenderer;

    /**
     * @var App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Directory\Model\CountryFactory
     */
    private $countryFactory;

    public function __construct(
        View\Element\Template\Context $context,
        Sales\Model\Order\Address\Renderer $addressRenderer,
        App\Config\ScopeConfigInterface $scopeConfig,
        Directory\Model\CountryFactory $countryFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->addressRenderer = $addressRenderer;
        $this->scopeConfig = $scopeConfig;
        $this->countryFactory = $countryFactory;
    }

    public function setLetterAddress(Sales\Model\Order\Address $address)
    {
        if ($this->addressRenderer) {
            $html = (string)$this->addressRenderer->format($address, 'html');
            $this->setHtmlLetterAddress($html);
        }
    }

    public function setHtmlLetterAddress(string $address)
    {
        $addressBlock = $this->getLayout()->getBlock('address');
        if ($addressBlock instanceof Address) {
            $addressBlock->setAddress($address);
        }
    }

    public function getStoreName()
    {
        return $this->scopeConfig->getValue(
            'general/store_information/name',
            Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getPhoneNumber()
    {
        return $this->scopeConfig->getValue(
            'general/store_information/phone',
            Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getEmailUs()
    {
        return $this->scopeConfig->getValue(
            'trans_email/ident_general/email',
            Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getOpeningHours()
    {
        return $this->scopeConfig->getValue(
            'general/store_information/hours',
            Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getPostcode()
    {
        return $this->scopeConfig->getValue(
            'general/store_information/postcode',
            Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getCity()
    {
        return $this->scopeConfig->getValue(
            'general/store_information/city',
            Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getRegionId(): string
    {
        return $this->scopeConfig->getValue(
            'general/store_information/region_id',
            Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getStreetLine1()
    {
        return $this->scopeConfig->getValue(
            'general/store_information/street_line1',
            Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getStreetLine2()
    {
        return $this->scopeConfig->getValue(
            'general/store_information/street_line2',
            Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getCountry()
    {
        $countryId =  $this->scopeConfig->getValue(
            'general/store_information/country_id',
            Store\Model\ScopeInterface::SCOPE_STORE
        );

        $country = $this->countryFactory->create()->loadByCode($countryId);

        return $country->getName();
    }

    /**
     * Formats order address to html, pdf and etc. formats
     *
     * @param \Magento\Sales\Model\Order\Address $address
     * @param string $format
     * @return null|string
     */
    public function formatAddress(\Magento\Sales\Model\Order\Address $address, $format)
    {
        return $this->addressRenderer->format($address, $format);
    }
}
