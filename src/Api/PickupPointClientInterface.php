<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Api;

interface PickupPointClientInterface
{
    public function getPickupPointsByPostcode(string $postcode): iterable;

    public function getPickupPointByCode(string $code): array;
}
