<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Api;

use BitBag\SyliusShippingExportPlugin\Entity\ShippingGatewayInterface;
use Setono\SyliusPickupPointPlugin\Model\ShipmentInterface;

interface ClientInterface
{
    public function setShippingGateway(ShippingGatewayInterface $shippingGateway): void;

    public function setShipment(ShipmentInterface $shipment): void;

    public function createExpedition(): string;
}
