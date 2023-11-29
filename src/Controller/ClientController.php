<?php

namespace App\Controller;

use App\Entity\Password;
use App\Entity\Theft;
use App\Helper\Helper;
use App\Repository\AddressRepository;
use App\Repository\ClientRepository;
use App\Repository\PasswordRepository;
use App\Repository\PolicyRepository;
use App\Repository\TheftRepository;
use App\Service\ClientService;
use App\Service\EmailService;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Component\Mailer\MailerInterface;
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

    #[Route('/', name: 'app_client_default')]
    public function defaultLogin(Request $request, SessionInterface $session): Response
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
                'error'=> ":".$e->getMessage(),
                'path' => $this->getPathEnv(),
            ]);
        }
    }

    #[Route('/client/dashboard', name: 'client_dashboard')]
    public function dashboard(Request $request, SessionInterface $session): Response
    {
        try {

            if (($valid = $this->validSession($session)) === false) {
                return $this->render('client/index.html.twig');
            }

            return $this->render('client/dashboard.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Dashboard',
                'session'=> $this->sessionDTO,
            ]);

        }catch (Exception $e) {
            return $this->render('error/500-cli.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Error',
                'error'=> ":".$e->getMessage()." file=".$e->getMessage()." line:".$e->getLine(),
            ]);
        }
    }



    #[Route('/client/{id}/policy', name: 'client_policy')]
    public function policy(Request $request, int $id,SessionInterface $session, PolicyRepository $policyRepository, ClientRepository $clientRepository): Response
    {
        try {

            if (($valid = $this->validSession($session)) === false) {
                return $this->render('client/index.html.twig');
            }

            $client = $clientRepository->find($id);

            if(!$client) {
                return $this->redirectToRoute('client_dashboard');
            }

           $list = $policyRepository->findBy(['client'=> $client]);

            //dd($list);

            return $this->render('client/policy.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Apolices',
                'list'=> $list,
                'session'=> $this->sessionDTO,
            ]);

        }catch (Exception $e) {
            return $this->render('error/500-cli.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Error',
                'error' => true,
                'type_error'=> "Error",
                'message_error'=> ":".$e->getMessage(),
                'session'=> $this->sessionDTO,
            ]);
        }
    }

    #[Route('/client/logout', name: 'app_user_logout_client')]
    public function logout( SessionInterface $session): Response
    {
        $session->invalidate();
        return $this->redirectToRoute('app_client');
    }
    //client_policy_open
    #[Route('/client/{id}/policy/open', name: 'client_policy_open')]
    public function policyOpen(int $id,SessionInterface $session,
                               PolicyRepository $policyRepository,
                                TheftRepository $theftRepository,
                                AddressRepository $addressRepository,
    ClientRepository $clientRepository
                               ): Response
    {
        try {

            if (($valid = $this->validSession($session)) === false) {
                return $this->render('client/index.html.twig');
            }


            $policy = $policyRepository->find($id);

            if(!$policy) {
                return $this->redirectToRoute('client_dashboard');
            }

            $theft = $theftRepository->findOneBy(["policy"=> $policy]);

            if(!$theft) {
                $theftStatus = false;
            }

            return $this->render('client/details_policy.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Detalhes de apolices',
                'policy'=> $policy,
                'theftStatus'=> $theftStatus??true,
                'theft'=> $theft,
                'session'=> $this->sessionDTO,
                'address'=>$addressRepository->findBy(["client"=> $clientRepository->find($this->sessionDTO->getIdUser())])
            ]);

        }catch (Exception $e) {
            return $this->render('error/500-cli.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Error',
                'error'=> ":".$e->getMessage()." file".$e->getFile()." line=".$e->getLine(),
                'session'=> $this->sessionDTO,
            ]);
        }
    }

    //policy_create_theft
    #[Route('/client/theftCreateView/{id}', name: 'policy_create_theft', methods:["POST", "GET"])]
    public function theftCreateView(Request $request, int $id, SessionInterface $session,
                                    PolicyRepository $policyRepository,
                                    AddressRepository $addressRepository,
                                    ClientRepository $clientRepository): Response
    {
        try {

            if (($valid = $this->validSession($session)) === false) {
                return $this->render('client/index.html.twig');
            }

            $policy = $policyRepository->find($id);

            if(!$policy) {
                return $this->redirectToRoute('client_dashboard');
            }

            $client = $clientRepository->find($this->sessionDTO->getIdUser());

            return $this->render('client/create.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Acionar Seguro',
                'session'=> $this->sessionDTO,
                'policy'=> $policy,
                'address'=>$addressRepository->findBy(["client"=> $client])
            ]);

        }catch (Exception $e) {
            return $this->render('error/500-cli.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Error',
                'error'=> ":".$e->getMessage()." file=".$e->getMessage()." line:".$e->getLine(),
                'session'=> $this->sessionDTO,
            ]);
        }
    }

    //

    #[Route('/client/theftCreatePersist/{id}', name: 'create_theft_persist', methods:["POST", "GET"])]
    public function theftCreatePersist(Request $request, int $id, SessionInterface $session,
                                    PolicyRepository $policyRepository,
                                    AddressRepository $addressRepository,
                                    ClientRepository $clientRepository,
                                       EntityManagerInterface $entityManager): Response
    {
        try {

            if (($valid = $this->validSession($session)) === false) {
                return $this->render('client/index.html.twig');
            }

            $policy = $policyRepository->find($id);

            if(!$policy) {
                return $this->redirectToRoute('client_dashboard');
            }

            $object = $clientRepository->find($this->sessionDTO->getIdUser());

            $addressObject = $addressRepository->findBy(["client"=> $object]);


            $desc = $request->request->get("desc")??'';
            $date_o = $request->request->get("date_o")??'';

            if(empty($desc) or empty($date_o) ) {
                return $this->render('admin_client/create.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title'=> 'Criar Cliente',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => "descricao e data  nao pode ser vazia",
                    'session'=> $this->sessionDTO,
                    'policy'=> $policy,
                    'address'=> $addressObject,
                    'client'=>$object

                ]);
            }


            $email = $request->request->get("email")??'';
            $date = $request->request->get("date")??'';
            $cellphone = $request->request->get("cellphone")??'';
            $phone = $request->request->get("phone")??'';
            $other_phone = $request->request->get("other_phone")??'';

            $cep = $request->request->get("cep")??'';
            $city = $request->request->get("city")??'';
            $state = $request->request->get("state")??'';
            $neighborhood = $request->request->get("neighborhood")??'';
            $address = $request->request->get("address")??'';
            $number = $request->request->get("number")??'';
            $reference = $request->request->get("reference")??'';

            if(empty($cep) or empty($city) or empty($state) or
                empty($neighborhood) or empty($address) or empty($number)) {

                return $this->render('admin_client/create.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title'=> 'Criar Cliente',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => "cep, cidade, estado, bairro e  number date and phone nao pode ser vazia",
                    'session'=> $this->sessionDTO,
                    'policy'=> $policy,
                    'address'=> $addressObject,
                    'client'=>$object

                ]);
            }


            $theft = new Theft();
            $theft->setDescription($desc);
            $theft->setDateOccurrence(new \DateTime($date_o));
            $theft->setPolicy($policy);

            $entityManager->persist($theft);
            $entityManager->flush();

            $object->setEmail($email);
            $object->setPhone($phone);
            $object->setCellphone($cellphone);
            $object->setOtherPhone($other_phone);
            $object->setDateOfBirth(new \DateTime($date));

            $entityManager->persist($object);
            $entityManager->flush();

            if(!is_null($object->getIdclient())) {

                $addressObject = $addressRepository->findOneBy(['client'=> $object]);

                $addressObject->setAddress($address);
                $addressObject->setClient($object);
                $addressObject->setNumber($number);
                $addressObject->setState($state);
                $addressObject->setCep($cep);
                $addressObject->setReference($reference);
                $addressObject->setCity($city);
                $addressObject->setNeighborhood($neighborhood);

                $entityManager->persist($addressObject);
                $entityManager->flush();

                return $this->redirectToRoute('client_dashboard');
            }


            return $this->render('client/create.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Acionar Seguro',
                'session'=> $this->sessionDTO,
                'policy'=> $policy,
                'address'=>$addressRepository->findBy(["client"=> $object])
            ]);

        }catch (Exception $e) {
            return $this->render('error/500-cli.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Error',
                'error'=> ":".$e->getMessage()." file=".$e->getMessage()." line:".$e->getLine(),
                'session'=> $this->sessionDTO,
            ]);
        }
    }

    #[Route('/client/forget', name: 'client_forget')]
    public function forget(Request $request, SessionInterface $session): Response
    {
        try {

            return $this->render('client/forget-password.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Reenviar Senha',
            ]);

        }catch (Exception $e) {
            return $this->render('error/500-cli.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Error',
                'error'=> ":".$e->getMessage()." file=".$e->getMessage()." line:".$e->getLine(),
            ]);
        }
    }

    #[Route('/updatePassword/{code}', name: 'updatePassword')]
    public function updatePassword(string $code, PasswordRepository $passwordRepository): Response
    {
        try {

            $password = $passwordRepository->findOneBy(['code'=> $code]);

            if ($password->getCode() == $code && is_null($password->getSubmit())) {

                    return $this->render('client/update_password.html.twig', [
                        'path' => $this->getPathEnv(),
                        'title'=> 'Atualizar Senha',
                        'object'=> $password
                    ]);

            }

                return $this->render('client/update_password.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title'=> 'Reenviar Senha',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => "link expirado",
                    'object'=> $password
                ]);


        }catch (Exception $e) {
            return $this->render('client/update_password.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Error',
                'error'=> ":".$e->getMessage()." file=".$e->getMessage()." line:".$e->getLine(),
                'type_error' => "Error",
                'message_error' => "cep, cidade, estado, bairro e  number date and phone nao pode ser vazia",
                'object'=> $validPasswords??null
            ]);
        }
    }

    //updatePassword

    #[Route('/client/forgetSend', name: 'app_forget_client', methods:["POST"])]
    public function forgetClient(Request $request,
    ClientRepository $clientRepository, EntityManagerInterface $entityManager,
                                /* MailerInterface $mailer*/): Response
    {
        try {

            $document = $request->request->get("document")??'';
            $email = $request->request->get("email")??'';

            if(empty($email) && empty($document)) {
                return $this->render('client/forget-password.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title'=> 'Reenviar Senha',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => "CPF/CNPJ, email nao podem ser vazios",
                    'email'=> $email,
                    'document'=> $document

                ]);
            }

            $client = $clientRepository->findOneBy(['document'=> Helper::cleanCnpjAndCpf($document), 'email'=> $email]);


            if(!$client) {
                return $this->render('client/forget-password.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title'=> 'Reenviar Senha',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => ": Nao encontramos, por favor entrar em contato com suporte",
                    'email'=> $email,
                    'document'=> $document
                ]);
            }


            $password =  new Password();

            $password->setClient($client);
            $password->setCode(md5($client->getFirstName().$client->getDocument().date("Td-m-Y-H-i-s")));
            $password->setCreated(new \DateTime());
            $password->setExpired((new \DateTime())->add(new \DateInterval('PT20M')));

            $entityManager->persist($password);
            $entityManager->flush();

            /* $emailService = new EmailService($mailer);
             $emailService->sendMailPassword($client->getEmail(),$client->getFirstName()." ".$client->getLastName(), $password->getCode());*/

            return $this->render('client/forget-password.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Reenviar Senha',
                'status'=>true
            ]);

        }catch (Exception $e) {
            return $this->render('client/forget-password.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Reenviar Senha',
                'error' => true,
                'type_error' => "Error",
                'message_error' => $e->getMessage(),
                'email'=> $email,
                'document'=> $document
            ]);
        }
    }

    #[Route('/updatePasswordPersist/{code}', name: 'updatePassword_persist', methods:["POST"])]
    public function updatePasswordPersist(string $code,Request $request, PasswordRepository $passwordRepository, EntityManagerInterface $entityManager): Response
    {
        try {


            $validPasswords = $passwordRepository->findOneBy(['code'=> $code]);

            if ($validPasswords ->getCode() == $code && !is_null($validPasswords ->getSubmit())) {

                return $this->render('client/update_password.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title'=> 'Atualziar Senha',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => "link expirado"
                ]);

            }

            $password = $request->request->get("password")??'';
            $password2 = $request->request->get("password2")??'';

            if($password != $password2) {
                return $this->render('client/update_password.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title'=> 'Atualziar Senha',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => "As senhas nao sao iguais"
                ]);
            }

            $client = $validPasswords->getClient();
            $client->setPassword(md5($password));

            $entityManager->persist($client);
            $entityManager->flush();

            $validPasswords->setSubmit(new \DateTime());
            $entityManager->persist($validPasswords);
            $entityManager->flush();

            return $this->redirectToRoute('client_login');

        }catch (Exception $e) {
            return $this->render('client/update_password.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Error',
                'message_error'=> ":".$e->getMessage()." file=".$e->getMessage()." line:".$e->getLine(),
                'type_error' => "Error",
                'error' => true,
            ]);
        }
    }

}
