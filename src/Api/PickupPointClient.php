<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Api;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class PickupPointClient implements PickupPointClientInterface
{
    private HttpClientInterface $httpClient;

    private string $apiPickupPointsKey;

    public function __construct(HttpClientInterface $httpClient, string $apiPickupPointsKey)
    {
        $this->httpClient = $httpClient;
        $this->apiPickupPointsKey = $apiPickupPointsKey;
    }

    public function getPickupPointsByPostcode(string $postcode): iterable
    {
        try {
            $params = [
                'carrier' => 'EXA',
                'key' => $this->apiPickupPointsKey,
                'address' => '',
                'zipCode' => $postcode,
                'city' => '',
                'countrycode' => '',
                'requestID' => '123',
                'date_from' => (new \DateTime())->format('d/m/Y'),
                'max_pudo_number' => '',
                'max_distance_search' => '',
                'weight' => '',
                'category' => '',
                'holiday_tolerant' => '',
            ];

            $url = sprintf('%s?%s', 'https://mypudo.pickup-services.com/mypudo/mypudo.asmx/GetPudoList', http_build_query($params));
            $response = $this->httpClient->request('GET', $url);

            $xmlContent = $response->getContent();

            $pickupsData = $this->transformXmlResponseContent($xmlContent);

            return $pickupsData;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getPickupPointByCode(string $code): array
    {
        try {
            $params = [
                'carrier' => 'EXA',
                'key' => $this->apiPickupPointsKey,
                'pudo_id' => $code,
            ];

            $url = sprintf('%s?%s', 'https://mypudo.pickup-services.com/mypudo/mypudo.asmx/GetPudoDetails', http_build_query($params));
            $response = $this->httpClient->request('GET', $url);

            $xmlContent = $response->getContent();

            $pickupsData = $this->transformXmlResponseContent($xmlContent);

            return $pickupsData;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function transformXmlResponseContent(string $xmlContent): array
    {
        $xml = simplexml_load_string($xmlContent);

        /** @var string $json */
        $json = json_encode($xml);

        /** @var array $array */
        $array = json_decode($json, true);

        /** @var array $itemsArray */
        $itemsArray = $array['PUDO_ITEMS'];

        /** @var array $data */
        $data = $itemsArray['PUDO_ITEM'];

        return $data;
    }
}
