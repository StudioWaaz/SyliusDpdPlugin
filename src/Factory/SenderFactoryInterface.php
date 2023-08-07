<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Factory;

use BitBag\SyliusShippingExportPlugin\Entity\ShippingGatewayInterface;
use TNTExpress\Model\Sender;

interface SenderFactoryInterface
{
    public function createNew(ShippingGatewayInterface $shippingGateway): Sender;
}
