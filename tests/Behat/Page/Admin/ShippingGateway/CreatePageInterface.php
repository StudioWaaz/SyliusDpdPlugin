<?php



declare(strict_types=1);

namespace Tests\Waaz\SyliusTntPlugin\Behat\Page\Admin\ShippingGateway;

use Sylius\Behat\Page\Admin\Crud\CreatePageInterface as BaseCreatePageInterface;

interface CreatePageInterface extends BaseCreatePageInterface
{
    public function selectShippingMethod($name): void;

    public function selectFieldOption($field, $option): void;

    public function fillField($field, $value): void;

    public function submit(): void;
}
