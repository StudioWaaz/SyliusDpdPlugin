<?php

declare(strict_types=1);

namespace Tests\Waaz\SyliusTntPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Setono\SyliusPickupPointPlugin\Model\PickupPointAwareInterface;
use Sylius\Behat\Page\Shop\Checkout\CompletePageInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Tests\Waaz\SyliusTntPlugin\Behat\Page\Shop\ShippingPickup\SelectShippingPageInterface;
use Webmozart\Assert\Assert;

final class ShippingContext implements Context
{
    /** @var SelectShippingPageInterface */
    private $selectShippingPage;

    /** @var CompletePageInterface */
    private $completePage;

    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var RepositoryInterface */
    private $orderRepository;

    public function __construct(
        SelectShippingPageInterface $selectShippingPage,
        CompletePageInterface $completePage,
        SharedStorageInterface $sharedStorage,
        RepositoryInterface $orderRepository
    ) {
        $this->selectShippingPage = $selectShippingPage;
        $this->completePage = $completePage;
        $this->sharedStorage = $sharedStorage;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @Then I choose the first option
     */
    public function iChooseTheFirstOption(): void
    {
        $this->selectShippingPage->chooseFirstShippingPointFromRadio();
    }

    /**
     * @Then the shipping method should have a pickup point
     */
    public function theShippingMethodShouldHaveAPickupPoint(): void
    {
        /** @var OrderInterface $order */
        $order = $this->orderRepository->findAll()[0];

        /** @var PickupPointAwareInterface $shipment */
        $shipment = $order->getShipments()->first();

        Assert::notNull($shipment->getPickupPointId());
    }

    /**
     * @Given I select :shippingMethod pickup point shipping method
     */
    public function iSelectPickupPointShippingMethod(string $shippingMethod): void
    {
        $this->selectShippingPage->selectPickupPointShippingMethod($shippingMethod);
    }
}
