<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Factory;

use Sylius\Component\Core\Model\ShipmentInterface;
use TNTExpress\Model\ParcelRequest;

class ParcelRequestFactory implements ParcelRequestFactoryInterface
{
    public function createNew(ShipmentInterface $shipment, string $weightUnit): ParcelRequest
    {
        $parcelRequest = new ParcelRequest();
        $parcelRequest->setSequenceNumber(1);

        $weight = $shipment->getShippingWeight() / 1000;

        if ($weightUnit === 'g') {
            $weight = $weight / 1000;
        }

        $parcelRequest->setWeight(sprintf('%.3f', $weight));

        return $parcelRequest;
    }
}
