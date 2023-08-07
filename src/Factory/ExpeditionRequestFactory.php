<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Factory;

use BitBag\SyliusShippingExportPlugin\Entity\ShippingGatewayInterface;
use TNTExpress\Model\ExpeditionRequest;
use TNTExpress\Model\ParcelRequest;
use TNTExpress\Model\Receiver;
use TNTExpress\Model\Sender;

class ExpeditionRequestFactory implements ExpeditionRequestFactoryInterface
{
    public function createNew(Sender $sender, Receiver $receiver, ParcelRequest $parcelRequest, ShippingGatewayInterface $shippingGateway): ExpeditionRequest
    {
        $expeditionRequest = new ExpeditionRequest();
        $expeditionRequest->setShippingDate(new \DateTime());
        $expeditionRequest->setAccountNumber($shippingGateway->getConfigValue('account_number'));
        $expeditionRequest->setSender($sender);
        $expeditionRequest->setReceiver($receiver);
        $expeditionRequest->setParcelsRequest([$parcelRequest]);
        $expeditionRequest->setLabelFormat($shippingGateway->getConfigValue('label_format'));

        return $expeditionRequest;
    }
}
