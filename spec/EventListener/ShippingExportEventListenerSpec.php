<?php



declare(strict_types=1);

namespace spec\Waaz\SyliusDpdPlugin\EventListener;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Filesystem\Filesystem;
use Setono\SyliusPickupPointPlugin\Model\ShipmentInterface;
use Waaz\SyliusDpdPlugin\Api\ShippingLabelFetcherInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Waaz\SyliusDpdPlugin\EventListener\ShippingExportEventListener;
use BitBag\SyliusShippingExportPlugin\Entity\ShippingExportInterface;
use BitBag\SyliusShippingExportPlugin\Entity\ShippingGatewayInterface;
use BitBag\SyliusShippingExportPlugin\Repository\ShippingExportRepository;

final class ShippingExportEventListenerSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(ShippingExportEventListener::class);
    }

    function let(
        Filesystem $filesystem,
        ShippingExportRepository $shippingExportRepository,
        ShippingLabelFetcherInterface $shippingLabelFetcher
    ): void
    {
        $this->beConstructedWith(
            $filesystem,
            $shippingExportRepository,
            'shippingLabel',
             $shippingLabelFetcher
        );
    }

    function it_exports_shipment(
        ResourceControllerEvent $event,
        ShippingExportInterface $shippingExport,
        ShippingGatewayInterface $shippingGateway,
        ShipmentInterface $shipment,
        ShippingLabelFetcherInterface $shippingLabelFetcher,
        ShippingExportEventListener $shippingExportEventListener
    ): void {
        $labelContent = 'labelContent';
        $shippingGatewayCode = "'dpd'";

        $event->getSubject()
            ->willReturn($shippingExport);

        $shippingExport->getShippingGateway()
            ->willReturn($shippingGateway);

        $shippingGateway->getCode()
            ->willReturn($shippingGatewayCode);

        $shippingExport->getShipment()
            ->willReturn($shipment);

        $shippingLabelFetcher->getLabelContent()
            ->willReturn($labelContent);

        $this->exportShipment($event);
    }

    function it_throws_exception_if_given_wrong_instance(
        ResourceControllerEvent $event,
        ShippingGatewayInterface $shippingGateway
    ): void {
        $event->getSubject()
            ->willReturn($shippingGateway);

        $this->shouldThrow(\Exception::class)
            ->during('exportShipment', [$event]);
    }

    function it_does_not_export_if_label_content_is_null(
        ResourceControllerEvent $event,
        ShippingExportInterface $shippingExport,
        ShippingGatewayInterface $shippingGateway,
        ShipmentInterface $shipment,
        ShippingLabelFetcherInterface $shippingLabelFetcher,
        Filesystem $filesystem
    ): void {
        $event->getSubject()->willReturn($shippingExport);
        $shippingExport->getShippingGateway()->willReturn($shippingGateway);
        $shippingGateway->getCode()->willReturn('dpd');
        $shippingExport->getShipment()->willReturn($shipment);

        $shippingLabelFetcher->createShipment($shippingGateway, $shipment)->shouldBeCalled();
        $shippingLabelFetcher->getLabelContent()->willReturn(null);

        // Verification that label saving and tracking code update are NOT called
        $shippingLabelFetcher->getTrackingCode()->shouldNotBeCalled();
        $filesystem->dumpFile(Argument::any(), Argument::any())->shouldNotBeCalled();

        $this->exportShipment($event);
    }
}
