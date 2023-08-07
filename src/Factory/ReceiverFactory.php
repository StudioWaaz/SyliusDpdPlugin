<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Factory;

use BitBag\SyliusShippingExportPlugin\Entity\ShippingGatewayInterface;
use Setono\SyliusPickupPointPlugin\Model\PickupPointAwareInterface;
use Setono\SyliusPickupPointPlugin\Model\PickupPointCode;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use TNTExpress\Model\Receiver;

class ReceiverFactory implements ReceiverFactoryInterface
{
    public function createNew(ShippingGatewayInterface $shippingGateway, ShipmentInterface $shipment): Receiver
    {
        $receiver = new Receiver();

        /** @var OrderInterface $order */
        $order = $shipment->getOrder();

        /** @var AddressInterface $address */
        $address = $order->getShippingAddress();

        /** @var CustomerInterface $customer */
        $customer = $order->getCustomer();

        $receiver->setContactFirstName($address->getFirstName())
            ->setContactLastName($address->getLastName())
            ->setName($address->getCompany())
            ->setAddress1($address->getStreet())
            ->setCity($address->getCity())
            ->setZipCode($address->getPostcode())
            ->setPhoneNumber($address->getPhoneNumber())
            ->setEmailAddress($customer->getEmail())
        ;

        if ($shipment instanceof PickupPointAwareInterface && $shipment->hasPickupPointId()) {
            /** @var string $pointId */
            $pointId = $shipment->getPickupPointId();
            $tntCode = PickupPointCode::createFromString($pointId);
            $receiver->setType('DROPOFFPOINT')
                ->setTypeId($tntCode->getIdPart())
            ;
        } else {
            $receiver->setType($shippingGateway->getConfigValue('receiver_type'));
        }

        return $receiver;
    }
}
