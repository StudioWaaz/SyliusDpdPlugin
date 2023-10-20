<?php

namespace spec\Waaz\SyliusDpdPlugin\Provider;

use PhpSpec\ObjectBehavior;
use Waaz\SyliusDpdPlugin\Provider\DpdProvider;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Waaz\SyliusDpdPlugin\Api\PickupPointClientInterface;
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
}
