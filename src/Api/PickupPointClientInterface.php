<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Api;

use Sylius\Component\Core\Model\AddressInterface;

interface PickupPointClientInterface
{
    public function getPickupPointsByShippingAddress(AddressInterface $address): iterable;

    public function getPickupPointByCode(string $code): array;
}
