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

        if(!$session->has("client_id") &&
            !$session->has("client_name") &&
            !$session->has("client_email")) {
             return false;
        }

        $this->sessionDTO = new SessionDTO();

        $this->sessionDTO->setIdUser($session->get("client_id"));
        $this->sessionDTO->setName($session->get("client_name"));
        $this->sessionDTO->setEmail($session->get("client_email"));
        $this->sessionDTO->setPassword($session->get("user_password")??null);
        $this->sessionDTO->setAdmin($session->get("user_admin")??'0');
        $this->sessionDTO->setPosition($session->get("user_position")??'');
        $this->sessionDTO->setAvatar($this->getPathEnvAvatar().$session->get("user_avatar"));

        return true;

    }

    protected function  getPathEnv(): string
    {
        $env = $_ENV["APP_ENV"]??'dev';

        if($env == 'dev') {
            return  "assets/";
        }else{
           return  "public/assets/";
        }

    }

    protected function  getPathEnvAvatar(): string
    {
        $env = $_ENV["APP_ENV"]??'dev';

        if($env == 'prod') {
            return  "public/";
        }else{
            return "../../";
        }
        return '';
    }

}