<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Factory;

use Sylius\Component\Core\Model\ShipmentInterface;
use TNTExpress\Model\ParcelRequest;

interface ParcelRequestFactoryInterface
{
    public function createNew(ShipmentInterface $shipment, string $weightUnit): ParcelRequest;
}
