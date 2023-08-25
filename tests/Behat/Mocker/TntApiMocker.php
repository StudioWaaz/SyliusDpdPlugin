<?php



declare(strict_types=1);

namespace Tests\Waaz\SyliusDpdPlugin\Behat\Mocker;

use Waaz\SyliusDpdPlugin\Api\SoapClientInterface;
use Sylius\Behat\Service\Mocker\MockerInterface;

class TntApiMocker
{
    /** @var MockerInterface */
    private $mocker;

    public function __construct(MockerInterface $mocker)
    {
        $this->mocker = $mocker;
    }

    public function performActionInApiSuccessfulScope(callable $action): void
    {
        $this->mockApiSuccessfulTntResponse();
        $action();
        $this->mocker->unmockAll();
    }

    private function mockApiSuccessfulTntResponse(): void
    {
        $createShipmentResult = (object) [
            'createShipmentResult' => (object) [
                'label' => (object) [
                    'labelContent' => 'test',
                    'labelType' => 't',
                ],
            ],
        ];

        $this
            ->mocker
            ->mockService(
                'waaz.tnt_plugin.api.soap_client',
                SoapClientInterface::class
            )
            ->shouldReceive('createShipment')
            ->andReturn($createShipmentResult)
        ;
    }
}
