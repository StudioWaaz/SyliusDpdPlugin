<?php

namespace spec\Waaz\SyliusTntPlugin\Provider;

use PhpSpec\ObjectBehavior;
use TNTExpress\Client\TNTClient;
use TNTExpress\Model\DropOffPoint;
use TNTExpress\Client\TNTClientInterface;
use Waaz\SyliusTntPlugin\Provider\TntProvider;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Setono\SyliusPickupPointPlugin\Model\PickupPoint;
use Setono\SyliusPickupPointPlugin\Model\PickupPointCode;
use Setono\SyliusPickupPointPlugin\Provider\ProviderInterface;

class TntProviderSpec extends ObjectBehavior
{
    public function let(TNTClient $client): void
    {
        //$factory->createNew()->willReturn(new PickupPoint());
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TntProvider::class);
    }

    public function it_implements_provider_interface(): void
    {
        $this->shouldImplement(ProviderInterface::class);
    }

    public function it_finds_multiple_pickup_points(
        TNTClientInterface $client,
        OrderInterface $order,
        AddressInterface $address
    ): void {
        $client->getDropOffPoints('64200', 'Biarritz')->willReturn([
            'resultat' => [
                'pakkeshops' => ['test', 'test'],
            ],
        ]);

        $address->getPostcode()->willReturn('64200');
        $address->getCity()->willReturn('Biarritz');

        $order->getShippingAddress()->willReturn($address);

        $pickupPoints = $this->findPickupPoints($order);
        //$pickupPoints->shouldBeArrayOfPickupPoints('0', '1'); // these are the ids to match
    }

    public function it_finds_pickup_by_code(
        TNTClientInterface $client,
        DropOffPoint $dropOffPoint
    ): void {
        $dropOffPoint->getXETTCode()->willReturn('test');
        $dropOffPoint->getName()->willReturn('pickup name');
        $dropOffPoint->getZipCode()->willReturn('64200');
        $dropOffPoint->getCity()->willReturn('Biarritz');
        $dropOffPoint->getAddress1()->willReturn('pickup address');
        $dropOffPoint->getAddress2()->willReturn('pickup address 2');
        $dropOffPoint->getLatitude()->willReturn('43.4833');
        $dropOffPoint->getLongitude()->willReturn('-1.55');

        
        $client->getDropOffPoints('64200', 'Biarritz')->willReturn([
            $dropOffPoint
        ]);
        $pickupPointCode = new PickupPointCode('test###64200###Biarritz', 'tnt', 'FR');
        $point = $this->findPickupPoint($pickupPointCode);
        $point->shouldReturnAnInstanceOf(PickupPoint::class);
        $point->getCode()->shouldReturnAnInstanceOf(PickupPointCode::class);
        $point->getCode()->getIdPart()->shouldReturn('test###64200###Biarritz');
        $point->getCode()->getProviderPart()->shouldReturn('tnt');
        $point->getCode()->getCountryPart()->shouldReturn('FR');
    }


}
