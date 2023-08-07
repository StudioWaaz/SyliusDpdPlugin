<?php

namespace spec\Waaz\SyliusTntPlugin\Action;

use TNTExpress\Model\City;
use PhpSpec\ObjectBehavior;
use TNTExpress\Client\TNTClient;
use TNTExpress\Client\TNTClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Response;
use Waaz\SyliusTntPlugin\Action\CityChoicesByZipCodeAction;

class CityChoicesByZipCodeActionSpec extends ObjectBehavior
{
    function let(
        TNTClientInterface $client
    ) {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CityChoicesByZipCodeAction::class);
    }

    function it_should_respond_to_index_action(
        TNTClientInterface $client,
        City $city
    ) {
        
        $city->getName()->willReturn('BIARRITZ');
        $client->getCitiesGuide('64200')->willReturn([$city]);
        $response = $this->__invoke('64200');


        $response->shouldHaveType('Symfony\Component\HttpFoundation\JsonResponse');
    }
}
