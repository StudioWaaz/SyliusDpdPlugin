<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Api;

use BitBag\SyliusShippingExportPlugin\Entity\ShippingGatewayInterface;
use Ekyna\Component\Dpd\EPrint;
use Ekyna\Component\Dpd\EPrint\Enum\ETypeContact;
use Ekyna\Component\Dpd\EPrint\Model\Contact;
use Ekyna\Component\Dpd\EPrint\Model\ParcelShop;
use Ekyna\Component\Dpd\EPrint\Model\ShopAddress;
use Ekyna\Component\Dpd\EPrint\Model\StdServices;
use Setono\SyliusPickupPointPlugin\Model\PickupPointCode;
use Setono\SyliusPickupPointPlugin\Model\ShipmentInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Waaz\SyliusDpdPlugin\Api\Model\ShipmentResponse;
use Waaz\SyliusDpdPlugin\Provider\ParcelProviderInterface;
use Webmozart\Assert\Assert;

class Client implements ClientInterface
{
    private ?ShippingGatewayInterface $shippingGateway = null;

    private ?ShipmentInterface $shipment = null;

    public function __construct(
        private bool $sandbox,
        private ParcelProviderInterface $parcelProvider,
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

    public function createExpedition(): ShipmentResponse
    {
        Assert::notNull($this->shipment, 'Shipment cannot be null');
        Assert::notNull($this->shippingGateway, 'Shipping gateway cannot be null');

        $parcels = $this->parcelProvider->getParcels($this->shipment);
        Assert::notEmpty($parcels, 'At least one parcel is required');

        $dpdClient = $this->buildDpdClient();

        if (count($parcels) === 1) {
            $request = $this->createLabelRequest();
            $request->labelType = $this->createLabelType();
            $request->receiveraddress = $this->createReceiverAddress();
            $request->shipperaddress = $this->createShipperAddress();

            $parcel = $parcels[0];
            $request->weight = (string) $parcel->getWeight();
            $request->referencenumber = (string) $parcel->getReference1();
            $request->reference2 = (string) $parcel->getReference2();
            $request->reference3 = (string) $parcel->getReference3();

            /** @var string $dpdType */
            $dpdType = $this->shippingGateway->getConfigValue('type');

            if ($dpdType === 'predict') {
                $request->services = $this->addPredictService();
            } elseif ($dpdType === 'relay') {
                $request->services = $this->addRelayService();
            }

            $response = $dpdClient->CreateShipmentWithLabelsBc($request);
            $result = $response->CreateShipmentWithLabelsBcResult;

            $trackingCode = null;
            /** @var EPrint\Model\ShipmentBc $shipment */
            $shipment = $result->shipments[0];

            $barCode = $shipment->Shipment->BarcodeId;
            if ('' !== $barCode) {
                $trackingCode = $barCode;
            }

            /** @var EPrint\Model\Label $label */
            $label = $result->labels[0];

            return new ShipmentResponse($label->label, $trackingCode);
        }

        $request = $this->createMultiLabelRequest();
        $request->receiveraddress = $this->createReceiverAddress();
        $request->shipperaddress = $this->createShipperAddress();

        /** @var string $dpdType */
        $dpdType = $this->shippingGateway->getConfigValue('type');

        $services = null;
        if ($dpdType === 'predict') {
            $services = $this->addMultiPredictService();
        }

        if (null !== $services) {
            $request->services = $services;
        }

        foreach ($parcels as $parcel) {
            $slave = new EPrint\Model\SlaveRequest();
            $slave->weight = (string) $parcel->getWeight();
            $slave->referencenumber = (string) $parcel->getReference1();
            $slave->reference2 = (string) $parcel->getReference2();
            $slave->reference3 = (string) $parcel->getReference3();

            $request->addSlave($slave);
        }

        $response = $dpdClient->CreateMultiShipmentBc($request);
        $result = $response->CreateMultiShipmentBcResult;

        $trackingCodes = [];
        $labelContents = [];

        /** @var EPrint\Model\ShipmentBc $shipmentBc */
        foreach ($result->shipments as $shipmentBc) {
            $barCode = $shipmentBc->Shipment->BarcodeId;
            if ('' !== $barCode) {
                $trackingCodes[] = $barCode;

                // On récupère l'étiquette pour chaque colis
                $labelRequest = new EPrint\Request\ReceiveLabelBcRequest();
                $labelRequest->customer = new EPrint\Model\Customer();
                /** @var string $centerNumber */
                $centerNumber = $this->shippingGateway->getConfigValue('customer_centernumber');
                /** @var string $countryCode */
                $countryCode = $this->shippingGateway->getConfigValue('customer_countrycode');
                /** @var string $customerNumber */
                $customerNumber = $this->shippingGateway->getConfigValue('customer_number');

                $labelRequest->customer->centernumber = $centerNumber;
                $labelRequest->customer->countrycode = $countryCode;
                $labelRequest->customer->number = $customerNumber;
                $labelRequest->shipmentNumber = $barCode;
                $labelRequest->labelType = $this->createLabelType();

                $labelResponse = $dpdClient->GetLabelBc($labelRequest);
                $labelResult = $labelResponse->GetLabelBcResult;

                /** @var EPrint\Model\Label $label */
                foreach ($labelResult->labels as $label) {
                    $labelContents[] = $label->label;
                }
            }
        }

        return new ShipmentResponse(implode('', $labelContents), implode(',', $trackingCodes));
    }

    private function buildDpdClient(): EPrint\Api
    {
        Assert::notNull($this->shippingGateway, 'Shipping gateway cannot be null');

        /** @var string $username */
        $username = $this->shippingGateway->getConfigValue('username');
        /** @var string $password */
        $password = $this->shippingGateway->getConfigValue('password');

        $ePrintConfig = [
            'login' => $username,
            'password' => $password,
            'cache' => $this->sandbox ? false : true,
            'debug' => $this->sandbox,
            'test' => $this->sandbox,
        ];

        $api = new EPrint\Api($ePrintConfig);

        return $api;
    }

    private function createLabelRequest(): EPrint\Request\StdShipmentLabelRequest
    {
        Assert::notNull($this->shippingGateway, 'Shipping gateway cannot be null');

        /** @var string $centerNumber */
        $centerNumber = $this->shippingGateway->getConfigValue('customer_centernumber');
        /** @var string $countryCode */
        $countryCode = $this->shippingGateway->getConfigValue('customer_countrycode');
        /** @var string $customerNumber */
        $customerNumber = $this->shippingGateway->getConfigValue('customer_number');

        $request = new EPrint\Request\StdShipmentLabelRequest();
        $request->customer_centernumber = $centerNumber;
        $request->customer_countrycode = $countryCode;
        $request->customer_number = $customerNumber;

        return $request;
    }

    private function createLabelType(): EPrint\Model\LabelType
    {
        Assert::notNull($this->shippingGateway, 'Shipping gateway cannot be null');
        $labelType = new EPrint\Model\LabelType();

        /** @var string $printerFormat */
        $printerFormat = $this->shippingGateway->getConfigValue('printer_format');
        $labelType->type = $printerFormat;

        return $labelType;
    }

    private function createReceiverAddress(): EPrint\Model\Address
    {
        Assert::notNull($this->shipment, 'Shipment cannot be null');

        /** @var OrderInterface $order */
        $order = $this->shipment->getOrder();

        /** @var AddressInterface $address */
        $address = $order->getShippingAddress();

        $receiveraddress = new EPrint\Model\Address();
        /** @var string $fullName */
        $fullName = $address->getFullName();
        $receiveraddress->name = $fullName;
        /** @var string $countryCode */
        $countryCode = $address->getCountryCode();
        $receiveraddress->countryPrefix = $countryCode;
        /** @var string $postcode */
        $postcode = $address->getPostcode();
        $receiveraddress->zipCode = $postcode;
        /** @var string $city */
        $city = $address->getCity();
        $receiveraddress->city = $city;
        /** @var string $street */
        $street = $address->getStreet();
        $receiveraddress->street = $street;
        /** @var string $phoneNumber */
        $phoneNumber = $address->getPhoneNumber();
        $receiveraddress->phoneNumber = $phoneNumber;

        return $receiveraddress;
    }

    private function createShipperAddress(): EPrint\Model\Address
    {
        Assert::notNull($this->shippingGateway, 'Shipping gateway cannot be null');

        $shipperaddress = new EPrint\Model\Address();
        /** @var string $name */
        $name = $this->shippingGateway->getConfigValue('sender_name');
        $shipperaddress->name = $name;
        /** @var string $countryCode */
        $countryCode = $this->shippingGateway->getConfigValue('sender_country');
        $shipperaddress->countryPrefix = $countryCode;
        /** @var string $postcode */
        $postcode = $this->shippingGateway->getConfigValue('sender_postalcode');
        $shipperaddress->zipCode = $postcode;
        /** @var string $city */
        $city = $this->shippingGateway->getConfigValue('sender_city');
        $shipperaddress->city = $city;
        /** @var string $street */
        $street = $this->shippingGateway->getConfigValue('sender_street');
        $shipperaddress->street = $street;
        /** @var string $phoneNumber */
        $phoneNumber = $this->shippingGateway->getConfigValue('sender_phone');
        $shipperaddress->phoneNumber = $phoneNumber;

        return $shipperaddress;
    }

    private function addPredictService(): StdServices
    {
        $services = new StdServices();
        $services->contact = new Contact();
        $services->contact->type = ETypeContact::PREDICT;

        Assert::notNull($this->shipment, 'Shipment cannot be null');
        /** @var OrderInterface $order */
        $order = $this->shipment->getOrder();

        /** @var AddressInterface $address */
        $address = $order->getShippingAddress();

        $phoneNumber = $address->getPhoneNumber();
        Assert::notNull($phoneNumber, 'Phone number cannot be null for predict service');
        $services->contact->sms = $phoneNumber;

        return $services;
    }

    private function addRelayService(): StdServices
    {
        Assert::notNull($this->shipment, 'Shipment cannot be null');
        $pickupPointId = $this->shipment->getPickupPointId();
        Assert::notNull($pickupPointId, 'Pickup point id cannot be null for relay service');

        $services = new StdServices();
        $services->parcelshop = new ParcelShop();
        $services->parcelshop->shopaddress = new ShopAddress();
        $setonoPickupPointCode = PickupPointCode::createFromString($pickupPointId);
        $services->parcelshop->shopaddress->shopid = $setonoPickupPointCode->getIdPart();

        /** @var OrderInterface $order */
        $order = $this->shipment->getOrder();

        /** @var AddressInterface $address */
        $address = $order->getShippingAddress();

        $phoneNumber = $address->getPhoneNumber();
        Assert::notNull($phoneNumber, 'Phone number cannot be null for relay service');

        $services->contact = new Contact();
        $services->contact->type = ETypeContact::AUTOMATIC_SMS;
        $services->contact->sms = $phoneNumber;

        return $services;
    }

    private function createMultiLabelRequest(): EPrint\Request\MultiShipmentRequest
    {
        Assert::notNull($this->shippingGateway, 'Shipping gateway cannot be null');

        /** @var string $centerNumber */
        $centerNumber = $this->shippingGateway->getConfigValue('customer_centernumber');
        /** @var string $countryCode */
        $countryCode = $this->shippingGateway->getConfigValue('customer_countrycode');
        /** @var string $customerNumber */
        $customerNumber = $this->shippingGateway->getConfigValue('customer_number');

        $request = new EPrint\Request\MultiShipmentRequest();
        $request->customer_centernumber = $centerNumber;
        $request->customer_countrycode = $countryCode;
        $request->customer_number = $customerNumber;

        return $request;
    }

    private function addMultiPredictService(): EPrint\Model\MultiServices
    {
        $services = new EPrint\Model\MultiServices();
        $services->contact = new Contact();
        $services->contact->type = ETypeContact::PREDICT;

        Assert::notNull($this->shipment, 'Shipment cannot be null');
        /** @var OrderInterface $order */
        $order = $this->shipment->getOrder();

        /** @var AddressInterface $address */
        $address = $order->getShippingAddress();

        $phoneNumber = $address->getPhoneNumber();
        Assert::notNull($phoneNumber, 'Phone number cannot be null for predict service');
        $services->contact->sms = $phoneNumber;

        return $services;
    }
}
