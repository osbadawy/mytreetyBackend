<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

trait ConsumesExternalServices

{


    /**
     * @param $baseUri
     * @param $method
     * @param $requestUrl
     * @param array $queryParams
     * @param array $formParams
     * @param array $headers
     * @param $hasFile
     * @return string
     * @throws GuzzleException
     */
    public function makeRequest($baseUri, $method, $requestUrl, $queryParams = [], $formParams = [], $headers = [], $hasFile = false): string

    {

        $client = new Client([

            'base_uri' => $baseUri,

        ]);

        $bodyType = 'form_params';

        if ($hasFile) {

            $bodyType = 'multipart';
            $multipart = [];

            foreach ($formParams as $name => $contents) {
                $multipart[] = [
                    'name' => $name,
                    'contents' => $contents
                ];
            }

        }

        $response = $client->request($method, $requestUrl, [
            'query' => $queryParams,
            $bodyType => $hasFile ? $multipart : $formParams,
            'headers' => $headers,
        ]);


        return $response->getBody()->getContents();

    }

}

