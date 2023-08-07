<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Api;

use BitBag\SyliusShippingExportPlugin\Entity\ShippingGatewayInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use TNTExpress\Model\Expedition;

interface ClientInterface
{
    public function setShippingGateway(ShippingGatewayInterface $shippingGateway): void;

    public function setShipment(ShipmentInterface $shipment): void;

    public function createExpedition(): Expedition;
}
