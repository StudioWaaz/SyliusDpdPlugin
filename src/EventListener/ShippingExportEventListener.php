<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\EventListener;

use BitBag\SyliusShippingExportPlugin\Entity\ShippingExportInterface;
use BitBag\SyliusShippingExportPlugin\Repository\ShippingExportRepository;
use Setono\SyliusPickupPointPlugin\Model\ShipmentInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\Filesystem\Filesystem;
use Waaz\SyliusDpdPlugin\Api\ShippingLabelFetcherInterface;
use Webmozart\Assert\Assert;

class ShippingExportEventListener
{
    public const DPD_GATEWAY_CODE = 'dpd';

    public function __construct(
        private Filesystem $filesystem,
        private ShippingExportRepository $shippingExportRepository,
        private string $shippingLabelsPath,
        private ShippingLabelFetcherInterface $shippingLabelFetcher,
    ) {
    }

    public function exportShipment(ResourceControllerEvent $event): void
    {
        $shippingExport = $event->getSubject();
        Assert::isInstanceOf($shippingExport, ShippingExportInterface::class);

        $shippingGateway = $shippingExport->getShippingGateway();
        Assert::notNull($shippingGateway);

        /** @var string $shippingGatewayCode */
        $shippingGatewayCode = $shippingGateway->getCode();

        if (str_starts_with($shippingGatewayCode, self::DPD_GATEWAY_CODE) === false) {
            return;
        }

        $shipment = $shippingExport->getShipment();
        Assert::isInstanceOf($shipment, ShipmentInterface::class);

        $this->shippingLabelFetcher->createShipment($shippingGateway, $shipment);

        $labelContent = $this->shippingLabelFetcher->getLabelContent();
        Assert::stringNotEmpty($labelContent);

        $this->saveShippingLabel($shippingExport, $labelContent, 'pdf'); // Save label
        $this->markShipmentAsExported($shippingExport); // Mark shipment as "Exported"
    }

    public function saveShippingLabel(
        ShippingExportInterface $shippingExport,
        string $labelContent,
        string $labelExtension,
    ): void {
        $labelPath = $this->shippingLabelsPath
            . '/' . $this->getFilename($shippingExport)
            . '.' . $labelExtension;

        $this->filesystem->dumpFile($labelPath, $labelContent);
        $shippingExport->setLabelPath($labelPath);

        $this->shippingExportRepository->add($shippingExport);
    }

    private function getFilename(ShippingExportInterface $shippingExport): string
    {
        $shipment = $shippingExport->getShipment();
        Assert::notNull($shipment);

        $order = $shipment->getOrder();
        Assert::notNull($order);

        /** @var string $orderNumber */
        $orderNumber = $order->getNumber();

        /** @var int $shipmentId */
        $shipmentId = $shipment->getId();

        return implode(
            '_',
            [
                $shipmentId,
                preg_replace('~[^A-Za-z0-9]~', '', $orderNumber),
            ],
        );
    }

    private function markShipmentAsExported(ShippingExportInterface $shippingExport): void
    {
        $shippingExport->setState(ShippingExportInterface::STATE_EXPORTED);
        $shippingExport->setExportedAt(new \DateTime());

        $this->shippingExportRepository->add($shippingExport);
    }
}
