<?php

namespace App\Controller;

use App\Entity\Capacity;
use App\Repository\CapacityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;

class CapacityController extends Controller
{
    #[Route('/capacity', name: 'app_capacity')]
    public function index(SessionInterface $session, CapacityRepository $capacityRepository): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            return $this->render('capacity/index.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Lista de Capacidades',
                'session'=> $this->sessionDTO,
                'object'=> 'Capacidade',
                'list'=> $capacityRepository->findBy([], ['title' => 'ASC'])
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

    #[Route('/capacity/viewCreate', name: 'app_capacity_view')]
    public function viewCreate(Request $request, SessionInterface $session): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            return $this->render('capacity/create.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Criar Capacidade',
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

    #[Route('/capacity/persistCreate', name: 'app_capacity_persist')]
    public function persistCreate(Request $request, SessionInterface $session,
                                  EntityManagerInterface $entityManager, CapacityRepository $capacityRepository): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $desc = $request->request->get("desc")??'';

            if(empty($desc)) {
                return $this->render('capacity/create.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title'=> 'Criar Capacidade',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => "Descricao nao pode ser vazia",
                    'desc'=> $desc,
                    'session'=> $this->sessionDTO,
                ]);
            }

            $existing = $capacityRepository->findOneBy(['title' => $desc]);

            if ($existing) {
                return $this->render('color/create.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title' => 'Criar Capacidade',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => "Esta capacidade já está cadastrada.",
                    'desc'=> $desc,
                    'session'=> $this->sessionDTO,
                ]);
            }


           $capacity = new Capacity();

            $capacity->setTitle($desc);
            $capacity->setCreated(new \DateTime());

            $entityManager->persist($capacity);
            $entityManager->flush();

            if(!is_null($capacity->getIdCapacity())) {
                return $this->redirectToRoute('app_capacity');
            }

            return $this->render('capacity/create.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Criar Capacidade',
                'error' => true,
                'type_error' => "Error",
                'message_error' => "Erro ao cadastrar a capacidade",
                'desc'=> $desc,
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

    #[Route('/capacity/viewEdit/{id}', name: 'app_capacity_view_edit')]
    public function capacityEdit(Request $request, int $id, SessionInterface $session,CapacityRepository $capacityRepository): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $object  = $capacityRepository->find($id);

            if(!$object) {
                return $this->redirectToRoute('app_capacity');
            }

            return $this->render('capacity/edit.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Editar Capacity',
                'session'=> $this->sessionDTO,
                'object'=> $object
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

    #[Route('/capacity/persistEdit/{id}', name: 'app_capacity_persist_edit')]
    public function persistEdit(Request $request, int $id, SessionInterface $session,
                                CapacityRepository $capacityRepository, EntityManagerInterface $entityManager): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $object  = $capacityRepository->find($id);

            if(!$object) {
                return $this->redirectToRoute('app_capacity');
            }

            $desc = $request->request->get("desc")??'';

            if(empty($desc)) {
                return $this->render('capacity/editar.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title'=> 'Editar Capacidade',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => "Descricao nao pode ser vazia",
                    'object'=> $object,
                    'session'=> $this->sessionDTO,
                ]);
            }

            $object->setTitle($desc);

            $entityManager->merge($object);
            $entityManager->flush();

            return $this->redirectToRoute('app_capacity');

        }catch (\Exception $e) {
            return $this->render('capacity/editar.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Editar Capacidade',
                'error' => true,
                'type_error' => "Error",
                'message_error' => $e->getMessage(),
                'color'=> $color??null,
                'session'=> $this->sessionDTO,
            ]);
        }
    }
}
