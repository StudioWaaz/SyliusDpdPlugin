<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Provider;

use Setono\SyliusPickupPointPlugin\Model\ShipmentInterface;
use Waaz\SyliusDpdPlugin\Api\Model\Parcel;

interface ParcelProviderInterface
{
    /**
     * @return Parcel[]
     */
    public function getParcels(ShipmentInterface $shipment): array;
}
