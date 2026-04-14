<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Api\Model;

class Parcel
{
    public function __construct(
        private float $weight,
        private ?string $reference1 = null,
        private ?string $reference2 = null,
        private ?string $reference3 = null,
    ) {
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getReference1(): ?string
    {
        return $this->reference1;
    }

    public function getReference2(): ?string
    {
        return $this->reference2;
    }

    public function getReference3(): ?string
    {
        return $this->reference3;
    }
}
