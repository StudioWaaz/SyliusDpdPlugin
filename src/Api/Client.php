<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Api;

use BitBag\SyliusShippingExportPlugin\Entity\ShippingGatewayInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use TNTExpress\Client\TNTClient;
use TNTExpress\Exception\InvalidPairZipcodeCityException;
use TNTExpress\Model\Address;
use TNTExpress\Model\Expedition;
use TNTExpress\Model\ExpeditionRequest;
use TNTExpress\Model\Service;
use Waaz\SyliusDpdPlugin\Factory\ExpeditionRequestFactoryInterface;
use Waaz\SyliusDpdPlugin\Factory\ParcelRequestFactoryInterface;
use Waaz\SyliusDpdPlugin\Factory\ReceiverFactoryInterface;
use Waaz\SyliusDpdPlugin\Factory\SenderFactoryInterface;
use Webmozart\Assert\Assert;

class Client implements ClientInterface
{
    private ?ShippingGatewayInterface $shippingGateway = null;

    private ?ShipmentInterface $shipment = null;

    public function __construct(
        private TNTClient $tntClient,
        private string $weightUnit,
        private SenderFactoryInterface $senderFactory,
        private ReceiverFactoryInterface $receiverFactory,
        private ParcelRequestFactoryInterface $parcelRequestFactory,
        private ExpeditionRequestFactoryInterface $expeditionRequestFactory,
    ) {
    }

    public function setShippingGateway(ShippingGatewayInterface $shippingGateway): void
    {
        $this->shippingGateway = $shippingGateway;
    }

    public function setShipment(ShipmentInterface $shipment): void
    {
        $this->shipment = $shipment;
    }

    public function createExpedition(): Expedition
    {
        $shippingGateway = $this->shippingGateway;
        Assert::isInstanceOf($shippingGateway, ShippingGatewayInterface::class, '$shippingGateway must be set before expedition creation.');
        $sender = $this->senderFactory->createNew($shippingGateway);

        $shipment = $this->shipment;
        Assert::isInstanceOf($shipment, ShipmentInterface::class, '$shipment must be set before expedition creation.');
        $receiver = $this->receiverFactory->createNew($shippingGateway, $shipment);
        $this->verifyAddresses([$receiver]);

        $parcelRequest = $this->parcelRequestFactory->createNew($shipment, $this->weightUnit);

        $expeditionRequest = $this->expeditionRequestFactory->createNew($sender, $receiver, $parcelRequest, $shippingGateway);

        $feasibility = $this->getFeasibility($expeditionRequest);
        $expeditionRequest->setServiceCode($feasibility->getServiceCode());

        return $this->tntClient->createExpedition($expeditionRequest);
    }

    private function verifyAddresses(array $addresses): void
    {
        foreach ($addresses as $address) {
            Assert::isInstanceOf($address, Address::class);
            if (false === $this->tntClient->checkZipcodeCityMatch($address->getZipCode(), $address->getCity())) {
                throw new InvalidPairZipcodeCityException($address->getZipCode(), $address->getCity());
            }
        }
    }

    /** Must be improved **/
    private function getFeasibility(ExpeditionRequest $expeditionRequest): Service
    {
        $feasibility = $this->tntClient->getFeasibility($expeditionRequest);

        $service = $feasibility[0];

        return $service;
    }
}
