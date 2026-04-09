<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Api;

use BitBag\SyliusShippingExportPlugin\Entity\ShippingGatewayInterface;
use Psr\Log\LoggerInterface;
use Setono\SyliusPickupPointPlugin\Model\ShipmentInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Waaz\SyliusDpdPlugin\Api\Model\ShipmentResponse;
use Webmozart\Assert\Assert;

class ShippingLabelFetcher implements ShippingLabelFetcherInterface
{
    private ?ShipmentResponse $response = null;

    public function __construct(
        private RequestStack $requestStack,
        private Client $client,
        private LoggerInterface $logger,
    ) {
    }

    public function createShipment(ShippingGatewayInterface $shippingGateway, ShipmentInterface $shipment): void
    {
        try {
            $this->client->setShippingGateway($shippingGateway);
            $this->client->setShipment($shipment);
            $this->response = $this->client->createExpedition();
        } catch (\Exception $exception) {
            $order = $shipment->getOrder();
            $this->logger->error('DPD Export Error: ' . $exception->getMessage(), [
                'exception' => $exception,
                'shipment_id' => $shipment->getId(),
            ]);
            Assert::isInstanceOf($order, OrderInterface::class);

            /** @var string $number */
            $number = $order->getNumber();

            $session = $this->requestStack->getSession();
            $flashBag = $session->getBag('flashes');
            Assert::isInstanceOf($flashBag, FlashBagInterface::class);

            $flashBag->add(
                'error',
                sprintf(
                    'DPD Service for #%s order: %s',
                    $number,
                    $exception->getMessage(),
                ),
            );
        }
    }

    public function getLabelContent(): ?string
    {
        if (null === $this->response) {
            return null;
        }

        $session = $this->requestStack->getSession();
        $flashBag = $session->getBag('flashes');
        Assert::isInstanceOf($flashBag, FlashBagInterface::class);

        $flashBag->add('success', 'bitbag.ui.shipment_data_has_been_exported');

        return $this->response->getLabelContent();
    }

    public function getTrackingCode(): ?string
    {
        if (null === $this->response) {
            return null;
        }

        return $this->response->getTrackingCode();
    }
}
