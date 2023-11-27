<?php

namespace App\Controller;

use App\Entity\ModelPhone;
use App\Entity\Policy;
use App\Helper\Helper;
use App\Repository\CapacityRepository;
use App\Repository\ClientRepository;
use App\Repository\ColorRepository;
use App\Repository\ModelPhoneRepository;
use App\Repository\PolicyRepository;
use App\Repository\TypeRoofingRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;

class PolicyController extends Controller
{
    #[Route('/policy/client/{id}', name: 'app_policy')]
    public function index(int $id, SessionInterface $session, PolicyRepository $policyRepository, ClientRepository $clientRepository): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $client = $clientRepository->find($id);

            if(!$client) {
                return $this->redirectToRoute('app_admin_client');
            }

            $list = $policyRepository->findBy(['client'=> $client]);

            return $this->render('policy/index.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Lista de Apolices',
                'session'=> $this->sessionDTO,
                'object'=> 'Apolices',
                'list'=> $list,
                'client'=>$client
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

    #[Route('/policy/client/{id}/view', name: 'app_policy_view_create')]
    public function createView(int $id, SessionInterface $session, ClientRepository $clientRepository,
                               ModelPhoneRepository $modelPhoneRepository, ColorRepository $colorRepository,
                               CapacityRepository $capacityRepository, TypeRoofingRepository $typeRoofingRepository ): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $client = $clientRepository->find($id);

            if(!$client) {
                return $this->redirectToRoute('app_policy',["id"=> $id]);
            }

            return $this->render('policy/create.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Create Apolice',
                'session'=> $this->sessionDTO,
                'object'=> 'Create Apolices',
                'client'=>$client,
                'color'=> $colorRepository->findBy([],["title"=> "ASC"]),
                'model'=> $modelPhoneRepository->findBy([],["title"=> "ASC"]),
                'capacity'=> $capacityRepository->findBy([],["title"=> "ASC"]),
                'typeRoofing'=>$typeRoofingRepository->findBy([],["title"=> "ASC"]),
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

    #[Route('/policy/client/{id}/persist', name: 'app_policy_view_create_persist')]
    public function createPersist(int $id, SessionInterface $session, ClientRepository $clientRepository,
                               ModelPhoneRepository $modelPhoneRepository, ColorRepository $colorRepository,
                               CapacityRepository $capacityRepository,TypeRoofingRepository $typeRoofingRepository,
                                 EntityManagerInterface $entityManager, Request $request ): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $client = $clientRepository->find($id);

            if(!$client) {
                return $this->redirectToRoute('app_policy',["id"=> $id]);
            }

            $roofing = $request->request->get("roofing")??'';
            $model = $request->request->get("model")??'';
            $color = $request->request->get("color")??'';
            $capacity = $request->request->get("capacity")??'';

            $policy = new Policy();

            $policy->setCreated(new \DateTime());
            $policy->setClient($client);
            $policy->setNumber(date('dmyHis').rand(0,999));
            $policy->setColor($colorRepository->find($color));
            $policy->setCapacity($capacityRepository->find($capacity));
            $policy->setModelPhone($modelPhoneRepository->find($model));
            $policy->setTypeRoofing($typeRoofingRepository->find($roofing));

            $entityManager->persist($policy);
            $entityManager->flush();

            return $this->redirectToRoute('app_policy',["id"=> $id]);

        }catch (\Exception $e) {

            return $this->render('error/500.html.twig',[
                'path' => $this->getPathEnv(),
                'title'=> 'Error',
                'session'=> $this->sessionDTO,
                'error'=> $e->getMessage()
            ]);
        }
    }



    #[Route('/policyEdit/client/{id}/view/{id_policy}', name: 'app_policy_view_edit')]
    public function editpersist(int $id, int $id_policy, SessionInterface $session, ClientRepository $clientRepository,
                             ModelPhoneRepository $modelPhoneRepository, ColorRepository $colorRepository,
                             CapacityRepository $capacityRepository,
                             TypeRoofingRepository $typeRoofingRepository ,
                             PolicyRepository $policyRepository): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $client = $clientRepository->find($id);

            if(!$client) {
                return $this->redirectToRoute('app_policy',["id"=> $id]);
            }

            $policy =  $policyRepository->find($id_policy);

            if(!$policy) {
                return $this->redirectToRoute('app_policy',["id"=> $id]);
            }

            return $this->render('policy/edit.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Editar Apolice',
                'session'=> $this->sessionDTO,
                'object'=> 'Editar Apolices',
                'client'=>$client,
                'policy'=>$policy,
                'color'=> $colorRepository->findBy([],["title"=> "ASC"]),
                'model'=> $modelPhoneRepository->findBy([],["title"=> "ASC"]),
                'capacity'=> $capacityRepository->findBy([],["title"=> "ASC"]),
                'typeRoofing'=>$typeRoofingRepository->findBy([],["title"=> "ASC"]),
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

    #[Route('/policyEdit/client/{id}/view/{id_policy}', name: 'app_policy_view_edit_persist')]
    public function editView(int $id, int $id_policy, SessionInterface $session, ClientRepository $clientRepository,
                             ModelPhoneRepository $modelPhoneRepository, ColorRepository $colorRepository,
                             CapacityRepository $capacityRepository,
                             TypeRoofingRepository $typeRoofingRepository ,
                             PolicyRepository $policyRepository,
                             EntityManagerInterface $entityManager, Request $request): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $client = $clientRepository->find($id);

            if(!$client) {
                return $this->redirectToRoute('app_policy',["id"=> $id]);
            }

            $policy =  $policyRepository->find($id_policy);

            if(!$policy) {
                return $this->redirectToRoute('app_policy',["id"=> $id]);
            }

            $roofing = $request->request->get("roofing")??'';
            $model = $request->request->get("model")??'';
            $color = $request->request->get("color")??'';
            $capacity = $request->request->get("capacity")??'';

            $policy->setCreated(new \DateTime());
            $policy->setClient($client);
            $policy->setColor($colorRepository->find($color));
            $policy->setCapacity($capacityRepository->find($capacity));
            $policy->setModelPhone($modelPhoneRepository->find($model));
            $policy->setTypeRoofing($typeRoofingRepository->find($roofing));

            $entityManager->persist($policy);
            $entityManager->flush();

            return $this->redirectToRoute('app_policy',["id"=> $id]);

        }catch (\Exception $e) {

            return $this->render('error/500.html.twig',[
                'path' => $this->getPathEnv(),
                'title'=> 'Error',
                'session'=> $this->sessionDTO,
                'error'=> $e->getMessage()
            ]);
        }
    }

    //app_client_policy
}
