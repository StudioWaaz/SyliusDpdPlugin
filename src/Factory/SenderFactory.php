<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Factory;

use BitBag\SyliusShippingExportPlugin\Entity\ShippingGatewayInterface;
use TNTExpress\Model\Sender;

class SenderFactory implements SenderFactoryInterface
{
    public function createNew(ShippingGatewayInterface $shippingGateway): Sender
    {
        $sender = new Sender();
        $sender->setName($shippingGateway->getConfigValue('sender_name'))
            ->setAddress1($shippingGateway->getConfigValue('sender_address1'))
            ->setAddress2($shippingGateway->getConfigValue('sender_address2'))
            ->setCity($shippingGateway->getConfigValue('sender_city'))
            ->setZipCode($shippingGateway->getConfigValue('sender_zip_code'))
            ->setPhoneNumber($shippingGateway->getConfigValue('sender_phone_number'))
            ->setContactFirstName($shippingGateway->getConfigValue('sender_contact_first_name'))
            ->setContactLastName($shippingGateway->getConfigValue('sender_contact_last_name'))
            ->setEmailAddress($shippingGateway->getConfigValue('sender_email_address'))
            ->setFaxNumber($shippingGateway->getConfigValue('sender_fax_number'))
            ->setType($shippingGateway->getConfigValue('sender_type'))
        ;

        return $sender;
    }
}
