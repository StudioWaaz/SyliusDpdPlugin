<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Waaz\SyliusTntPlugin\Behat\Page\Shop\Checkout;

use Behat\Mink\Element\NodeElement;
use Sylius\Behat\Page\Shop\Checkout\AddressPage as BaseAddressPage;

class AddressPage extends BaseAddressPage {

    public function selectBillingCountry(string $country): void
    {
        $this->getElement('billing_country')->selectOption($country);
    }
    
    public function selectBillingPostcode(string $postcode): void
    {
        $this->getElement('billing_postcode')->setValue($postcode);
    }
    
    public function getAvailableBillingCities(): array
    {
        $this->waitForOption(5, 'billing_city_select');
        return $this->getOptionsFromSelect($this->getElement('billing_city_select'));
    }

    public function selectBillingCity(string $city): void
    {
        $this->waitForOption(5, 'billing_city_select');
        $this->getElement('billing_city_select')->selectOption($city);
    }

    public function fillBillingData(string $field, string $value): void
    {
        $this->getElement('billing_' . $field)->setValue($value);
    }

    /**
     * @return string[]
     */
    private function getOptionsFromSelect(NodeElement $element): array
    {
        return array_map(
            /** @return string[] */
            static fn (NodeElement $element): string => $element->getText(),
            $element->findAll('css', 'option[value!=""]'),
        );
    }
    
    private function waitForElement(int $timeout, string $elementName): bool
    {
        return $this->getDocument()->waitFor($timeout, fn () => $this->hasElement($elementName));
    }

    private function waitForOption(int $timeout, string $elementName): void
    {
        $parentElement = $this->getElement($elementName);
        $this->getDocument()->waitFor($timeout, fn () => count($parentElement->findAll('css', 'option')) > 0);
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'billing_country' => '[data-test-billing-country]',
            'billing_postcode' => '[data-test-billing-postcode]',
            'billing_city' => '[data-test-billing-city]',
            'billing_city_select' => '#city-select-0',
            'billing_phone_number' => '#sylius_checkout_address_billingAddress_phoneNumber',
        ]);
    }
}