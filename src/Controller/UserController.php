<?php

namespace App\Controller;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    #[Route('/login', name: 'app_user_login')]
    public function index(Request $request, UserRepository $userRepository,SessionInterface $session): Response
    {
        if ($request->isMethod('POST')) {

            $email = $request->request->get("email");
            $password = $request->request->get("password");

            if(empty($email) or empty($password)) {
                return $this->render('index/index.html.twig', [
                    'error' => true,
                    'type_error'=> "Error",
                    'message_error'=> "Login invalid"
                ]);
            }

            $user = $userRepository->findOneBy([
                'email' => $email,
                'password' => md5($password),
                'active' => 1,
            ]);

            if($user) {

                $session->start();
                $session->set("user_id",$user->getIdUser());
                $session->set("user_password", $user->getPassword());
                $session->set("user_name", $user->getName());
                $session->set("user_email", $user->getEmail());
                $session->set("user_admin", $user->getAdmin());
                $session->set("user_position", $user->getPosition());

                return $this->redirectToRoute('app_dashboard');

            }

            return $this->render('index/index.html.twig', [
                'error' => true,
                'type_error'=> "Error",
                'message_error'=> "Login invalid"
            ]);

        }

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
