<?php



declare(strict_types=1);

namespace Tests\Waaz\SyliusTntPlugin\Behat\Context\Ui\Shop;

use Webmozart\Assert\Assert;
use Behat\Behat\Context\Context;
use Sylius\Behat\Page\Shop\Checkout\AddressPageInterface;

final class ShippingAddressContext implements Context
{
    public function __construct(
        private AddressPageInterface $addressPage,
    ) {
    }

    /**
     * @When I specify billing country as :countryName
     */
    public function iSpecifyBillingCountryAs(string $countryName): void
    {
        $this->addressPage->selectBillingCountry($countryName);
    }

    /**
     * @When I specify billing postcode as :postcode
     */
    public function iSpecifyBillingPostcodeAs(string $postcode): void
    {
        $this->addressPage->selectBillingPostcode($postcode);
    }

    /**
     * @Then I should have :cityName city available to choose from
     */
    public function shouldHaveCountriesToChooseFrom(string $cityName): void
    {
        $availableBillingCities = $this->addressPage->getAvailableBillingCities();
        Assert::inArray($cityName, $availableBillingCities, "$cityName is not in cities select.");
    }

    /**
     * @When I select billing city as :cityName
     */
    public function iSelectBillingCityAs(string $cityName): void
    {
        $this->addressPage->selectBillingCity($cityName);
    }

    /**
     * @When I specify billing first name as :firstName
     */
    public function iSpecifyBillingFirstNameAs(string $firstName): void
    {
        $this->addressPage->fillBillingData('first_name', $firstName);
    }

    /**
     * @When I specify billing last name as :lastName
     */
    public function iSpecifyBillingLastNameAs(string $lastName): void
    {
        $this->addressPage->fillBillingData('last_name', $lastName);
    }

    /**
     * @When I specify billing street as :street
     */
    public function iSpecifyBillingStreetAs(string $street): void
    {
        $this->addressPage->fillBillingData('street', $street);
    }

    /**
     * @When I specify billing phone as :phoneNumber
     */
    public function iSpecifyBillingPhoneNumberAs(string $phoneNumber): void
    {
        $this->addressPage->fillBillingData('phone_number', $phoneNumber);
    }

    /**
     * @When I specify input billing city as :cityName
     */
    public function iSelectHiddenBillingCityAs(string $cityName): void
    {
        $this->addressPage->fillBillingData('city', $cityName);
    }

    /**
     * @Then I should be notified that the city does not match the postcode
     */
    public function iShouldBeNotifiedThatTheCityDoesNotMatchThePostcode(): void
    {
        Assert::true($this->addressPage->checkValidationMessageFor('billing_city', 'City and postal code do not match'));
    }
}