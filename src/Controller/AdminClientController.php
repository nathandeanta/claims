<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Client;
use App\Helper\Helper;
use App\Repository\AddressRepository;
use App\Repository\ClientRepository;
use App\Repository\PolicyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;


class AdminClientController extends Controller
{
    #[Route('/adminClient', name: 'app_admin_client')]
    public function index(SessionInterface $session, ClientRepository $clientRepository): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            return $this->render('admin_client/index.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Lista de Clientes',
                'session'=> $this->sessionDTO,
                'object'=> 'Clientes',
                'list'=> $clientRepository->findBy([], ['first_name' => 'ASC'])
            ]);

        }catch (\Exception $e) {

            return $this->render('error/500.html.twig',[
                'path' => $this->getPathEnv(),
                'title'=> 'Error',
                'session'=> $this->sessionDTO,
                'error'=> $e->getMessage()
            ]);
        }
    }

    #[Route('/client/viewCreate', name: 'app_admin_client_view')]
    public function viewCreate( SessionInterface $session): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            return $this->render('admin_client/create.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Criar Cliente',
                'session'=> $this->sessionDTO,
            ]);

        }catch (\Exception $e) {
            return $this->render('error/500.html.twig',[
                'path' => $this->getPathEnv(),
                'title'=> 'Error',
                'session'=> $this->sessionDTO,
                'error'=> $e->getMessage()
            ]);
        }
    }

    #[Route('/adminClient/persistCreate', name: 'app_admin_client_persist')]
    public function persistCreate(Request $request, SessionInterface $session,
                                  EntityManagerInterface $entityManager, ClientRepository $clientRepository): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $first_name = $request->request->get("first_name")??'';
            $last_name = $request->request->get("last_name")??'';
            $document = $request->request->get("document")??'';
            $rg = $request->request->get("rg")??'';
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
                    'first_name'=> $first_name,
                    'last_name'=> $last_name,
                    'document'=> $document,
                    'rg'=> $rg,
                    'email'=> $email,
                    'date'=> $date,
                    'phone'=> $phone,
                    'cellphone'=> $cellphone,
                    'other_phone'=> $other_phone,
                    'session'=> $this->sessionDTO,
                    'city'=> $city,
                    "cep"=> $cep,
                    "state"=> $state,
                    "neighborhood"=> $neighborhood,
                    "address"=> $address,
                    "reference"=> $reference,
                    "number"=> $number
                ]);
            }


            if(empty($document) or empty($rg) or empty($email) or empty($date)
                or empty($first_name) or empty($last_name) or empty($cellphone)) {
                return $this->render('admin_client/create.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title'=> 'Criar Cliente',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => "Name , sobrenome,CPF/CNPJ,email,date and phone nao pode ser vazia",
                    'first_name'=> $first_name,
                    'last_name'=> $last_name,
                    'document'=> $document,
                    'rg'=> $rg,
                    'email'=> $email,
                    'date'=> $date,
                    'phone'=> $phone,
                    'cellphone'=> $cellphone,
                    'other_phone'=> $other_phone,
                    'session'=> $this->sessionDTO,
                    'city'=> $city,
                    "cep"=> $cep,
                    "state"=> $state,
                    "neighborhood"=> $neighborhood,
                    "address"=> $address,
                    "reference"=> $reference,
                    "number"=> $number
                ]);
            }

            $existing = $clientRepository->findOneBy(["document"=> $document, "rg"=> $rg]);

            if ($existing) {
                return $this->render('admin_client/create.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title' => 'Criar Cliente',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => "Este cliente já está cadastrada.",
                    'first_name'=> $first_name,
                    'last_name'=> $last_name,
                    'document'=> $document,
                    'rg'=> $rg,
                    'email'=> $email,
                    'date'=> $date,
                    'phone'=> $phone,
                    'cellphone'=> $cellphone,
                    'other_phone'=> $other_phone,
                    'session'=> $this->sessionDTO,
                    'city'=> $city,
                    "cep"=> $cep,
                    "state"=> $state,
                    "neighborhood"=> $neighborhood,
                    "address"=> $address,
                    "reference"=> $reference,
                    "number"=> $number
                ]);
            }


            $object = new Client();

            $object->setFirstName($first_name);
            $object->setLastName($last_name);
            $object->setDocument(Helper::cleanCnpjAndCpf($document));
            $object->setRg(Helper::cleanCnpjAndCpf($rg));
            $object->setEmail($email);
            $object->setPhone($phone);
            $object->setCellphone($cellphone);
            $object->setOtherPhone($other_phone);
            $object->setDateOfBirth(new \DateTime($date));
            $object->setCreated(new \DateTime());
            $object->setPassword(md5("senha123"));
            $object->setActive("1");

            $entityManager->persist($object);
            $entityManager->flush();

            if(!is_null($object->getIdclient())) {

                $addressObject = new Address();
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

                return $this->redirectToRoute('app_admin_client');
            }

            return $this->render('type_roofing/create.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Criar Cobertura',
                'error' => true,
                'type_error' => "Error",
                'message_error' => "Erro ao cadastrar a Cobertura",
                'session'=> $this->sessionDTO,
                'first_name'=> $first_name,
                'last_name'=> $last_name,
                'document'=> $document,
                'rg'=> $rg,
                'email'=> $email,
                'date'=> $date,
                'phone'=> $phone,
                'cellphone'=> $cellphone,
                'other_phone'=> $other_phone,
                'city'=> $city,
                "cep"=> $cep,
                "state"=> $state,
                "neighborhood"=> $neighborhood,
                "address"=> $address,
                "reference"=> $reference,
                "number"=> $number
            ]);

        }catch (\Exception $e) {
            return $this->render('error/500.html.twig',[
                'path' => $this->getPathEnv(),
                'title'=> 'Error',
                'session'=> $this->sessionDTO,
                'error'=> $e->getMessage()
            ]);
        }
    }

    #[Route('/adminClient/viewEdit/{id}', name: 'app_admin_client_view_edit')]
    public function capacityEdit(Request $request, int $id, SessionInterface $session,
                                 ClientRepository $clientRepository,
                                 AddressRepository $addressRepository, PolicyRepository $policyRepository): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $object  = $clientRepository->find($id);

            if(!$object) {
                return $this->redirectToRoute('app_admin_client');
            }

            return $this->render('admin_client/edit.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Editar Cliente',
                'session'=> $this->sessionDTO,
                'object'=> $object,
                'address'=>$addressRepository->findBy(["client"=> $object]),
                'count_apolice'=> $policyRepository->countApolicesByclient($object)
            ]);

        }catch (\Exception $e) {
            return $this->render('error/500.html.twig',[
                'path' => $this->getPathEnv(),
                'title'=> 'Error',
                'session'=> $this->sessionDTO,
                'error'=> $e->getMessage()." file".$e->getFile()." line".$e->getLine()
            ]);
        }
    }

    #[Route('/adminClient/persistEdit/{id}', name: 'app_admin_client_persist_edit')]
    public function persistEdit(Request $request, int $id, SessionInterface $session,
                                  EntityManagerInterface $entityManager, ClientRepository $clientRepository, AddressRepository $addressRepository): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $first_name = $request->request->get("first_name")??'';
            $last_name = $request->request->get("last_name")??'';
            $document = $request->request->get("document")??'';
            $rg = $request->request->get("rg")??'';
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
                    'first_name'=> $first_name,
                    'last_name'=> $last_name,
                    'document'=> $document,
                    'rg'=> $rg,
                    'email'=> $email,
                    'date'=> $date,
                    'phone'=> $phone,
                    'cellphone'=> $cellphone,
                    'other_phone'=> $other_phone,
                    'session'=> $this->sessionDTO,
                    'city'=> $city,
                    "cep"=> $cep,
                    "state"=> $state,
                    "neighborhood"=> $neighborhood,
                    "address"=> $address,
                    "reference"=> $reference,
                    "number"=> $number
                ]);
            }

            if(empty($document) or empty($rg) or empty($email) or empty($date)
                or empty($first_name) or empty($last_name) or empty($cellphone)) {
                return $this->render('admin_client/create.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title'=> 'Criar Cliente',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => "Name , sobrenome,CPF/CNPJ,email,date and phone nao pode ser vazia",
                    'first_name'=> $first_name,
                    'last_name'=> $last_name,
                    'document'=> $document,
                    'rg'=> $rg,
                    'email'=> $email,
                    'date'=> $date,
                    'phone'=> $phone,
                    'cellphone'=> $cellphone,
                    'other_phone'=> $other_phone,
                    'session'=> $this->sessionDTO,
                    'city'=> $city,
                    "cep"=> $cep,
                    "state"=> $state,
                    "neighborhood"=> $neighborhood,
                    "address"=> $address,
                    "reference"=> $reference,
                    "number"=> $number
                ]);
            }

            $object = $clientRepository->find($id);

            if (!$object) {
                return $this->redirectToRoute('app_admin_client');
            }

            $object->setFirstName($first_name);
            $object->setLastName($last_name);
            $object->setDocument(Helper::cleanCnpjAndCpf($document));
            $object->setRg(Helper::cleanCnpjAndCpf($rg));
            $object->setEmail($email);
            $object->setPhone($phone);
            $object->setCellphone($cellphone);
            $object->setOtherPhone($other_phone);
            $object->setDateOfBirth(new \DateTime($date));
            $object->setCreated(new \DateTime());
            $object->setActive(1);

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

                return $this->redirectToRoute('app_admin_client');
            }

            return $this->render('type_roofing/create.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Criar Cobertura',
                'error' => true,
                'type_error' => "Error",
                'message_error' => "Erro ao cadastrar a Cobertura",
                'session'=> $this->sessionDTO,
                'first_name'=> $first_name,
                'last_name'=> $last_name,
                'document'=> $document,
                'rg'=> $rg,
                'email'=> $email,
                'date'=> $date,
                'phone'=> $phone,
                'cellphone'=> $cellphone,
                'other_phone'=> $other_phone,
                'city'=> $city,
                "cep"=> $cep,
                "state"=> $state,
                "neighborhood"=> $neighborhood,
                "address"=> $address,
                "reference"=> $reference,
                "number"=> $number
            ]);

        }catch (\Exception $e) {
            return $this->render('error/500.html.twig',[
                'path' => $this->getPathEnv(),
                'title'=> 'Error',
                'session'=> $this->sessionDTO,
                'error'=> $e->getMessage()
            ]);
        }
    }


    //app_admin_client_persist_edit
}
