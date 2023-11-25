<?php

namespace App\Service;

use Exception;
use GuzzleHttp\Client;

class MarchineLearningService
{
    protected $uri;
    private Client $client;
    private $headers;
    public function __construct()
    {
        $this->client = new Client(['base_uri' => $_ENV["URI_MARCHINE"] ?? null]);
        $this->headers = ['Content-Type' => 'application/json'];

        $this->uri = $_ENV["NYLA_URI"] ?? null;
    }

    /**
     * @throws Exception
     */
    private function generateToken()
    {
        $response  = $this->client->post("/login",[
            'headers' => $this->headers,
            'body'=> $this->getUserLogin(),
            'connect_timeout' => 300,
            'timeout' => 300
        ]);

        if($response->getStatusCode() == 200) {

            $body = $response->getBody();
            $content = $body->getContents();

             $token =  json_decode($content,true);

             if(!isset($token["access_token"])) {
                 return false;
             }

            $this->headers = ['Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token["access_token"] ];

            return true;

        }

        return false;

    }



    public function getAll()
    {
       $token =  $this->generateToken();

       if(!$token) {
           return["status"=> false, "error"=> "Error no token"];
       }

       $response = $this->client->get("/list/theft",[
           'headers' => $this->headers,
           'connect_timeout' => 300,
           'timeout' => 300
       ]);

        if($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $content = $body->getContents();

            return (json_decode($content,true));
        }

        return["status"=> false, "error"=> "Error na busca"];

    }

    private function getUserLogin(): bool|string
    {
        $user = $_ENV["USER_MARCHINE"]??null;
        $password = $_ENV["PASSWORD_MARCHINE"]??null;

        if(is_null($user) or is_null($password)) {
            throw new Exception("ENV not found ");
        }

        return json_encode(["username"=> $user, "password"=> $password]);
    }


}