<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Provider;

use Setono\SyliusPickupPointPlugin\Model\ShipmentInterface;
use Waaz\SyliusDpdPlugin\Api\Model\Parcel;

class DefaultParcelProvider implements ParcelProviderInterface
{
    public function __construct(private string $weightUnit)
    {
    }

    public function getParcels(ShipmentInterface $shipment): array
    {
        if ($this->weightUnit === 'g') {
            $weight = $shipment->getShippingWeight() / 1000;
        } else {
            $weight = $shipment->getShippingWeight();
        }

        $weight = round($weight, 2);

        return [new Parcel($weight)];
    }
}
