<?php

declare(strict_types=1);

namespace spec\Waaz\SyliusDpdPlugin\Api;

use PhpSpec\ObjectBehavior;
use TNTExpress\Model\Sender;
use TNTExpress\Model\Service;
use TNTExpress\Model\Receiver;
use TNTExpress\Client\TNTClient;
use TNTExpress\Model\Expedition;
use TNTExpress\Model\ParcelRequest;
use Waaz\SyliusDpdPlugin\Api\Client;
use Waaz\SyliusDpdPlugin\Api\ClientInterface;
use Webmozart\Assert\InvalidArgumentException;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Ekyna\Component\Dpd\Exception\ClientException;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Waaz\SyliusDpdPlugin\Factory\SenderFactoryInterface;
use TNTExpress\Exception\InvalidPairZipcodeCityException;
use Waaz\SyliusDpdPlugin\Factory\ReceiverFactoryInterface;
use BitBag\SyliusShippingExportPlugin\Entity\ShippingGateway;
use Waaz\SyliusDpdPlugin\Factory\ParcelRequestFactoryInterface;
use Waaz\SyliusDpdPlugin\Factory\ExpeditionRequestFactoryInterface;
use BitBag\SyliusShippingExportPlugin\Entity\ShippingGatewayInterface;

class ClientSpec extends ObjectBehavior
{
    function let(): void 
    {
        $this->beConstructedWith('g', true);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(Client::class);
        $this->shouldHaveType(ClientInterface::class);
    }

    function it_creates_request_data_shipment(
        ShippingGatewayInterface $shippingGateway,
        ShipmentInterface $shipment,
        AddressInterface $address,
        OrderInterface $order,
        CustomerInterface $customer,
    ): void {
        $shippingGateway->getConfigValue('username')->willReturn('test');
        $shippingGateway->getConfigValue('password')->willReturn('test');

        $shippingGateway->getConfigValue('sender_name')->willReturn('Studio Waaz');
        $shippingGateway->getConfigValue('sender_street')->willReturn('23 Avenue d\'Aguilera');
        $shippingGateway->getConfigValue('sender_city')->willReturn('Biarritz');
        $shippingGateway->getConfigValue('sender_postalcode')->willReturn('64200');
        $shippingGateway->getConfigValue('sender_country')->willReturn('FR');
        $shippingGateway->getConfigValue('sender_phone')->willReturn('0559595959');
        $shippingGateway->getConfigValue('sender_email')->willReturn('developpement@studiowaaz.com');
        $shippingGateway->getConfigValue('printer_format')->willReturn('PDF');
        $shippingGateway->getConfigValue('customer_centernumber')->willReturn(77);
        $shippingGateway->getConfigValue('customer_countrycode')->willReturn(250);
        $shippingGateway->getConfigValue('customer_number')->willReturn(18028);

        $this->setShippingGateway($shippingGateway);
        $this->setShipment($shipment);

        $address->getFullName()->willReturn('Mongabure Ibes');
        $address->getCountryCode()->willReturn('FR');

        $address->getStreet()->willReturn('9 rue Port du Temple');
        $address->getPhoneNumber()->willReturn('0500000000');
        $address->getCity()->willReturn('BIARRITZ');
        $address->getPostcode()->willReturn('64200');


        $customer->getEmail()->willReturn('alex@durand.fr');
        
        $order->getShippingAddress()->willReturn($address);
        $order->getCustomer()->willReturn($customer);
        $shipment->getOrder()->willReturn($order);
        $shipment->getShippingWeight()->willReturn(2000);

        $this->shouldThrow(ClientException::class)->during('createExpedition');
    }
    function it_should_not_allow_create_expedition_without_shipment(ShippingGatewayInterface $shippingGateway): void
    {
        $this->shouldThrow(new InvalidArgumentException('Shipment cannot be null'))->during('createExpedition');
    }

    function it_should_not_allow_create_expedition_without_shipping_gateway(ShipmentInterface $shipment): void
    {
        $this->setShipment($shipment);

        $this->shouldThrow(new InvalidArgumentException('Shipping gateway cannot be null'))->during('createExpedition');
    }
}
