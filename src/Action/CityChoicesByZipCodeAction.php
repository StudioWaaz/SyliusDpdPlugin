<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Action;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TNTExpress\Client\TNTClientInterface;
use TNTExpress\Exception\ClientException;
use TNTExpress\Model\City;

final class CityChoicesByZipCodeAction
{
    public function __construct(
        private TNTClientInterface $tntClient,
    ) {
    }

    public function __invoke(string $postcode): JsonResponse
    {
        if ('' === $postcode) {
            throw new NotFoundHttpException();
        }

        try {
            $result = $this->tntClient->getCitiesGuide($postcode);
            $cities = array_map(fn (City $city): string => ucwords(strtolower($city->getName() ?? '')), $result);
        } catch (ClientException $e) {
            $cities = [];
        }

        return new JsonResponse($cities);
    }
}
