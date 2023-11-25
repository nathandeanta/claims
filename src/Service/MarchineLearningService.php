<?php

namespace App\Service;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

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

    public function createLearning(string $type, string $desc)
    {
        $token =  $this->generateToken();

        if(!$token) {
            return["status"=> false, "error"=> "Error no token"];
        }
        $body = ["desc"=> $desc, "type"=> $type];

        try {


            $response = $this->client->post("create/theft", [
                'headers' => $this->headers,
                'body' => json_encode($body),
                'connect_timeout' => 300,
                'timeout' => 300
            ]);

            if ($response->getStatusCode() == 200) {

                return ["status" => true, "mgs" => "Cadastrado com sucesso"];

            } else {

                if ($response->getStatusCode() == 400) {
                    return ["status" => false, "error" => "Ja existe esse aprendizado cadastrado"];
                }

                return ["status" => false, "error" => "erro identificado"];

            }
        }catch (RequestException $e) {

            $response = $e->getResponse();

            if ($response) {
                $statusCode = $response->getStatusCode();
                $body = json_decode($response->getBody(), true);

                if ($statusCode === 400 && isset($body['error']) && $body['error'] === 'is already registered') {

                    return ["status" => false, "error" => ': O item já está registrado.'];

                } else {

                    return ["status" => false, "error" => ': Erro desconhecido: ' . $response->getBody()->getContents()];
                }
            } else {

                return ["status" => false, "error" => ": Erro na requisição:". $e->getMessage()];
            }
        }


    }

    public function details($id)
    {
        $token =  $this->generateToken();

        if(!$token) {
            return["status"=> false, "error"=> "Error no token"];
        }

        try {

            $response = $this->client->get("/details/theft/".$id, [
                'headers' => $this->headers,
                'connect_timeout' => 300,
                'timeout' => 300
            ]);

            if ($response->getStatusCode() == 200) {

                $body = $response->getBody();
                $content = $body->getContents();

                return (json_decode($content,true));

            } else {

                if ($response->getStatusCode() == 400) {
                    return ["status" => false, "error" => "nao encontrado"];
                }

                return ["status" => false, "error" => "nao encontrado"];

            }
        }catch (RequestException $e) {

            $response = $e->getResponse();

            if ($response) {
                $statusCode = $response->getStatusCode();
                $body = json_decode($response->getBody(), true);

                if ($statusCode === 400 && isset($body['error'])) {

                    return ["status" => false, "error" => ': nao encontrado'];
                } else {

                    return ["status" => false, "error" => ': nao encontrado ' . $response->getBody()->getContents()];
                }
            } else {

                return ["status" => false, "error" => ": Erro na requisição:". $e->getMessage()];
            }
        }
    }


}