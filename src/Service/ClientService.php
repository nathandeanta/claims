<?php

namespace App\Service;

use App\Entity\Client;
use App\Helper\Helper;
use App\Repository\ClientRepository;

class ClientService
{
    private ClientRepository $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }


    public function login($email, $password): ?Client
    {
        //senha123
        return (
            $this->clientRepository->findOneBy(
            [
                'document'=> Helper::cleanCnpjAndCpf($email),
                'password' => md5($password),
                'active'=> '1'
            ])
        );

    }
}