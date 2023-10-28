<?php



declare(strict_types=1);

namespace Tests\Waaz\SyliusDpdPlugin\Behat\Mocker;

use Waaz\SyliusDpdPlugin\Api\ClientInterface;
use Sylius\Behat\Service\Mocker\MockerInterface;

class DpdApiMocker
{
    /** @var MockerInterface */
    private $mocker;

    public function __construct(MockerInterface $mocker)
    {
        $this->mocker = $mocker;
    }

    public function performActionInApiSuccessfulScope(callable $action): void
    {
        $this->mockApiSuccessfulDpdResponse();
        $action();
        $this->mocker->unmockAll();
    }

    private function mockApiSuccessfulDpdResponse(): void
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
                'waaz.dpd_plugin.api.client',
                ClientInterface::class
            )
            ->shouldReceive('createShipment')
            ->andReturn($createShipmentResult)
        ;
    }
}
