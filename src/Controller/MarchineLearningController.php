<?php

namespace App\Controller;

use App\Service\MarchineLearningService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class MarchineLearningController extends Controller
{
    #[Route('/marchine/learning', name: 'app_marchine_learning')]
    public function index(SessionInterface $session): Response
    {
        try {

            if (($valid = $this->validSession($session)) === false) {
                return $this->render('index/index.html.twig');
            }

            if ($this->sessionDTO->getAdmin() != "1") {
                return $this->render(
                    'user/nopermission.html.twig',
                    ['title' => 'Not permitted',
                        'nopermission' => 'You do not have permissions to access this area, contact the administrator',
                        'session' => $this->sessionDTO,
                        'path' => $this->getPathEnv()],
                );
            }

            $marchineLearning = new MarchineLearningService();

            $list = $marchineLearning->getAll();

            if(isset($list["theft_list"])) {
                $list = $list["theft_list"];
            }

            return $this->render('marchine_learning/index.html.twig', [
                'session' => $this->sessionDTO,
                'path' => $this->getPathEnv(),
                'title' => 'Lista de aprendizado',
                'theft_list' => $list
            ]);

        }catch (\Exception $e) {

            return $this->render('marchine_learning/index.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> ' Lista de aprendizado',
                'error' => true,
                'type_error' => "Error",
                'message_error' => "Error ".$e->getMessage(),
                'session' => $this->sessionDTO,
                'theft_list' => null
            ]);
        }
    }
}
