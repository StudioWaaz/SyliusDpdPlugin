<?php



declare(strict_types=1);

namespace Tests\Waaz\SyliusDpdPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Tests\Waaz\SyliusDpdPlugin\Behat\Mocker\DpdApiMocker;
use Tests\BitBag\SyliusShippingExportPlugin\Behat\Page\Admin\ShippingExport\IndexPageInterface;

final class ShippingExportContext implements Context
{
    /** @var IndexPageInterface */
    private $indexPage;

    /** @var DpdApiMocker */
    private $dpdApiMocker;

    public function __construct(
        IndexPageInterface $indexPage,
        DpdApiMocker $dpdApiMocker
    ) {
        $this->dpdApiMocker = $dpdApiMocker;
        $this->indexPage = $indexPage;
    }

    /**
     * @When I export all new shipments to dhl api
     */
    public function iExportAllNewShipments(): void
    {
        $this->dpdApiMocker->performActionInApiSuccessfulScope(function () {
            $this->indexPage->exportAllShipments();
        });
    }

    /**
     * @When I export first shipment to dhl api
     */
    public function iExportFirsShipments(): void
    {
        $this->dpdApiMocker->performActionInApiSuccessfulScope(function () {
            $this->indexPage->exportFirsShipment();
        });
    }
}
