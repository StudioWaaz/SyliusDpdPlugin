<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Api;

use BitBag\SyliusShippingExportPlugin\Entity\ShippingGatewayInterface;
use Sylius\Component\Core\Model\ShipmentInterface;

interface ShippingLabelFetcherInterface
{
    public function createShipment(ShippingGatewayInterface $shippingGateway, ShipmentInterface $shipment): void;

    public function getLabelContent(): ?string;
}
