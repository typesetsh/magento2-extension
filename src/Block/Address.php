<?php

namespace Typesetsh\Pdf\Block;


class Address extends Letter
{
    /** @var string */
    protected $address = '';

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }
}
