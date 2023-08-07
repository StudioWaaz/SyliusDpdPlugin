<?php



declare(strict_types=1);

namespace Tests\Waaz\SyliusTntPlugin\Behat\Page\Admin\ShippingGateway;

use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;
use Tests\BitBag\SyliusShippingExportPlugin\Behat\Behaviour\ContainsError;

final class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    use ContainsError;

    public function selectShippingMethod($name): void
    {
        $this->getDocument()->selectFieldOption('Shipping method', $name);
    }

    public function selectFieldOption($field, $option): void
    {
        $this->getDocument()->selectFieldOption($field, $option);
    }

    public function fillField($field, $value): void
    {
        $this->getDocument()->fillField($field, $value);
    }

    public function submit(): void
    {
        $this->create();
    }
}
