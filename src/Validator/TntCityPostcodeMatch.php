<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class TntCityPostcodeMatch extends Constraint
{
    public string $cityAndPostcodeDoesNotMatchMessage = 'waaz.tnt_plugin.city_postcode_match.messages.invalid';

    public function validatedBy(): string
    {
        return 'TntCityPostMatchConstraint';
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
