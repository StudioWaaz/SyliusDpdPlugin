<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Factory;

use BitBag\SyliusShippingExportPlugin\Entity\ShippingGatewayInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use TNTExpress\Model\Receiver;

interface ReceiverFactoryInterface
{
    public function createNew(ShippingGatewayInterface $shippingGateway, ShipmentInterface $shipment): Receiver;
}
