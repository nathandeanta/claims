<?php

namespace App\Controller;

use App\DTO\SessionDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Controller extends AbstractController
{
    protected  SessionDTO $sessionDTO;

    public function validSession(SessionInterface $session)
    {
        $session->start();

        if(!$session->has("user_id") &&
            !$session->has("user_email") &&
            !$session->has("user_password")) {
             return false;
        }

        $this->sessionDTO = new SessionDTO();

        $this->sessionDTO->setIdUser($session->get("user_id"));
        $this->sessionDTO->setName($session->get("user_name"));
        $this->sessionDTO->setEmail($session->get("user_email"));
        $this->sessionDTO->setPassword($session->get("user_password"));
        $this->sessionDTO->setAdmin($session->get("user_admin"));
        $this->sessionDTO->setPosition($session->get("user_position"));

    }

}