<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Provider;

use Setono\SyliusPickupPointPlugin\Model\PickupPoint;
use Setono\SyliusPickupPointPlugin\Model\PickupPointCode;
use Setono\SyliusPickupPointPlugin\Model\PickupPointInterface;
use Setono\SyliusPickupPointPlugin\Provider\Provider;
use Sylius\Component\Core\Model\OrderInterface;
use Waaz\SyliusDpdPlugin\Api\PickupPointClientInterface;
use Webmozart\Assert\Assert;

class DpdProvider extends Provider
{
    private PickupPointClientInterface $client;

    public function __construct(PickupPointClientInterface $client)
    {
        // TODO: write logic here
        $this->client = $client;
    }

    /**
     * A unique code identifying this provider
     */
    public function getCode(): string
    {
        return 'dpd-fr';
    }

    /**
     * Will return the name of this provider
     */
    public function getName(): string
    {
        return 'DPD France';
    }

    /**
     * Will return an array of pickup points
     *
     * @return iterable<PickupPointInterface>
     */
    public function findPickupPoints(OrderInterface $order): iterable
    {
        $orderAddress = $order->getShippingAddress();
        Assert::notNull($orderAddress, 'Order address cannot be null');

        $postcode = $orderAddress->getPostcode();
        Assert::notNull($postcode, 'Postcode cannot be null');

        $rawData = $this->client->getPickupPointsByPostcode($postcode);

        $pickups = $this->transformRawDataToPickupPoints($rawData);

        return $pickups;
    }

    public function findPickupPoint(PickupPointCode $code): ?PickupPointInterface
    {
        $rawPickup = $this->client->getPickupPointByCode($code->getIdPart());

        $pickup = $this->transformRawDataToPickupPoint($rawPickup);

        return $pickup;
    }

    /**
     * Returns all pickup points for this provider
     *
     * @return iterable<PickupPointInterface>|PickupPointInterface[]
     */
    public function findAllPickupPoints(): iterable
    {
        return [];
    }

    /**
     * @return iterable<PickupPointInterface>|PickupPointInterface[]
     */
    private function transformRawDataToPickupPoints(iterable $rawData): iterable
    {
        /** @var array $rawPickup */
        foreach ($rawData as $rawPickup) {
            yield $this->transformRawDataToPickupPoint($rawPickup);
        }
    }

    private function transformRawDataToPickupPoint(array $rawData): PickupPointInterface
    {
        $pickup = new PickupPoint();
        $pickup->setCode(new PickupPointCode($rawData['PUDO_ID'], $this->getCode(), 'FR'));
        $pickup->setName((string) $rawData['NAME']);
        $pickup->setAddress((string) $rawData['ADDRESS1']);
        $pickup->setZipCode((string) $rawData['ZIPCODE']);
        $pickup->setCity((string) $rawData['CITY']);
        $pickup->setCountry('FR');

        $rawLatitude = (string) $rawData['LATITUDE'];
        $latitude = str_replace(',', '.', $rawLatitude);

        $rawLongitude = (string) $rawData['LONGITUDE'];
        $longitude = str_replace(',', '.', $rawLongitude);

        $pickup->setLatitude((float) $latitude);
        $pickup->setLongitude((float) $longitude);

        return $pickup;
    }
}
