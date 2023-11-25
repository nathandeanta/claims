<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Service\ClientService;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ClientController extends Controller
{

    #[Route('/client', name: 'app_client')]
    public function index(Request $request, SessionInterface $session): Response
    {
        try {

            if (($valid = $this->validSession($session)) === true) {
                return $this->render('client/dashboard.html.twig');
            }

            return $this->render('client/index.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> ' Login Cliente'
            ]);

        }catch (Exception $e) {
            return $this->render('client/index.html.twig', [
                'controller_name' => 'ClientController',
                'path' => $this->getPathEnv(),
                'title'=> ' Login Cliente'
            ]);
        }
    }

    #[Route('/client/login', name: 'client_login')]
    public function login(Request $request,SessionInterface $session, ClientRepository $clientRepository): Response
    {
        try {

            if ($request->isMethod('POST')) {

                $email = $request->request->get("user_login");
                $password = $request->request->get("password");

                if(empty($email) or empty($password)) {
                    return $this->render('client/index.html.twig', [
                        'error' => true,
                        'type_error'=> "Error",
                        'message_error'=> ":user e senha nao podem ser vazios",
                        'title'=> ' Login Cliente',
                        'path' => $this->getPathEnv()
                    ]);
                }

                $clientService = new ClientService($clientRepository);
                $client = $clientService->login($email,$password);


                if($client){
                    $session->start();
                    $session->set("client_id", $client->getIdClient());
                    $session->set("client_email", $client->getEmail());
                    $session->Set("client_name", $client->getFirstName()." ".$client->getLastName());
                    return $this->redirectToRoute('client_dashboard');
                }


                return $this->render('client/index.html.twig', [
                    'title'=> 'Login Cliente',
                    'error' => true,
                    'type_error'=> "Error",
                    'message_error'=> ":Usuario Invalido",
                    'user'=> $email,
                    'path' => $this->getPathEnv(),
                ]);

            }

            return $this->render('client/index.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Login Cliente',
                'path' => $this->getPathEnv()
            ]);

        }catch (Exception $e) {
            return $this->render('client/index.html.twig', [
                'title' => 'Login Cliente',
                'error' => true,
                'type_error'=> "Error",
                'message_error'=> ":".$e->getMessage(),
                'path' => $this->getPathEnv(),
            ]);
        }
    }

    #[Route('/client/dashboard', name: 'client_dashboard')]
    public function dashboard(Request $request, SessionInterface $session): Response
    {
        try {

            if (($valid = $this->validSession($session)) === false) {
                dd("error valid login");
                return $this->render('client/index.html.twig');
            }

            return $this->render('client/dashboard.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Dashboard',
            ]);

        }catch (Exception $e) {
            return $this->render('client/index.html.twig', [
                'controller_name' => 'ClientController',
                'path' => $this->getPathEnv(),
                'title'=> 'Login',
                'error' => true,
                'type_error'=> "Error",
                'message_error'=> ":".$e->getMessage(),
            ]);
        }
    }

}
