<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Factory;

use BitBag\SyliusShippingExportPlugin\Entity\ShippingGatewayInterface;
use TNTExpress\Model\ExpeditionRequest;
use TNTExpress\Model\ParcelRequest;
use TNTExpress\Model\Receiver;
use TNTExpress\Model\Sender;

interface ExpeditionRequestFactoryInterface
{
    public function createNew(Sender $sender, Receiver $receiver, ParcelRequest $parcelRequest, ShippingGatewayInterface $shippingGateway): ExpeditionRequest;
}
