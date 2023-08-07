<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Validator;

use Sylius\Component\Addressing\Model\AddressInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use TNTExpress\Client\TNTClientInterface;
use TNTExpress\Exception\ClientException;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class TntCityPostcodeMatchValidator extends ConstraintValidator
{
    public function __construct(
        private TNTClientInterface $tntClient,
    ) {
    }

    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof AddressInterface) {
            throw new UnexpectedValueException($value, AddressInterface::class);
        }

        if (!$constraint instanceof TntCityPostcodeMatch) {
            throw new UnexpectedValueException($constraint, TntCityPostcodeMatch::class);
        }

        if ($value->getCountryCode() != 'FR') {
            return;
        }

        $postcode = $value->getPostcode();
        $city = $value->getCity();

        if (false === (null === $postcode) && false === (null === $city)) {
            try {
                $result = $this->tntClient->getCitiesGuide($postcode);
                foreach ($result as $tntCity) {
                    $cityName = ucwords(strtolower($tntCity->getName() ?? ''));
                    if ($cityName === $city) {
                        return;
                    }
                }
            } catch (ClientException $e) {
            }
        }

        $this->context
            ->buildViolation($constraint->cityAndPostcodeDoesNotMatchMessage)
            ->atPath('city')
            ->addViolation()
        ;
    }
}
