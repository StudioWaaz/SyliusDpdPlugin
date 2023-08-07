<?php



declare(strict_types=1);

namespace Tests\Waaz\SyliusTntPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Tests\Waaz\SyliusTntPlugin\Behat\Mocker\TntApiMocker;
use Tests\BitBag\SyliusShippingExportPlugin\Behat\Page\Admin\ShippingExport\IndexPageInterface;

final class ShippingExportContext implements Context
{
    /** @var IndexPageInterface */
    private $indexPage;

    /** @var TntApiMocker */
    private $tntApiMocker;

    public function __construct(
        IndexPageInterface $indexPage,
        TntApiMocker $tntApiMocker
    ) {
        $this->tntApiMocker = $tntApiMocker;
        $this->indexPage = $indexPage;
    }

    /**
     * @When I export all new shipments to dhl api
     */
    public function iExportAllNewShipments(): void
    {
        $this->tntApiMocker->performActionInApiSuccessfulScope(function () {
            $this->indexPage->exportAllShipments();
        });
    }

    /**
     * @When I export first shipment to dhl api
     */
    public function iExportFirsShipments(): void
    {
        $this->tntApiMocker->performActionInApiSuccessfulScope(function () {
            $this->indexPage->exportFirsShipment();
        });
    }
}
