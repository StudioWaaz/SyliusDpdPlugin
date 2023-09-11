<?php

namespace spec\Waaz\SyliusDpdPlugin\Provider;

use PhpSpec\ObjectBehavior;
use Waaz\SyliusDpdPlugin\Provider\DpdProvider;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Setono\SyliusPickupPointPlugin\Model\PickupPoint;
use Waaz\SyliusDpdPlugin\Api\PickupPointClientInterface;
use Setono\SyliusPickupPointPlugin\Model\PickupPointCode;
use Setono\SyliusPickupPointPlugin\Provider\ProviderInterface;

class DpdProviderSpec extends ObjectBehavior
{
    public function let(PickupPointClientInterface $client): void
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DpdProvider::class);
    }

    public function it_implements_provider_interface(): void
    {
        $this->shouldImplement(ProviderInterface::class);
    }

    public function it_finds_multiple_pickup_points(
        OrderInterface $order,
        AddressInterface $address,
        PickupPointClientInterface $client
    ): void {
        // $client->getPickupPointsByPostcode('64200')->willReturn([
        //     [null, null]
        // ]);

        $address->getPostcode()->willReturn('64200');

        $order->getShippingAddress()->willReturn($address);

        $pickupPoints = $this->findPickupPoints($order);
        $pickupPoints->shouldBeArrayOfPickupPoints('0', '1'); // these are the ids to match
    }

    // public function it_finds_pickup_by_code(
    //     PickupPointClientInterface $client
    // ): void {

    //     $pickupPointCode = new PickupPointCode('test###64200###Biarritz', 'tnt', 'FR');
    //     $client->getPickupPointByCode('test###64200###Biarritz')->willReturn([
    //         'test' => 'test'
    //     ]);
    //     $point = $this->findPickupPoint($pickupPointCode);
    //     $point->shouldReturnAnInstanceOf(PickupPoint::class);
    //     $point->getCode()->shouldReturnAnInstanceOf(PickupPointCode::class);
    //     $point->getCode()->getIdPart()->shouldReturn('test###64200###Biarritz');
    //     // $point->getCode()->getProviderPart()->shouldReturn('tnt');
    //     // $point->getCode()->getCountryPart()->shouldReturn('FR');
    // }


}
