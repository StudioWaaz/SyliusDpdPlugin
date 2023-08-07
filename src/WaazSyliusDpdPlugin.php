<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class WaazSyliusDpdPlugin extends Bundle
{
    use SyliusPluginTrait;
}
