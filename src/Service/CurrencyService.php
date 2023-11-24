<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CurrencyService
{
    /**
     * @throws GuzzleException
     */
    public function getCoinToBRL($coin= "EUR-BRL")
    {

        $client = new Client();

        $response = $client->get("https://economia.awesomeapi.com.br/last/{$coin}");

        if ($response->getStatusCode() === 200) {

            $data = json_decode($response->getBody(), true);

            $key = str_replace("-", "", $coin);

            return $data[$key]['bid'];
        } else {

            return  ["status"=>false, "error" => "Falha ao acessar a API"];
        }
    }



}