<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Api\Model;

class ShipmentResponse
{
    public function __construct(
        private string $labelContent,
        private ?string $trackingCode = null,
    ) {
    }

    public function getLabelContent(): string
    {
        return $this->labelContent;
    }

    public function getTrackingCode(): ?string
    {
        return $this->trackingCode;
    }
}
