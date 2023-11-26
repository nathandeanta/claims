<?php

namespace App\Controller;

use App\Entity\ModelPhone;
use App\Repository\ModelPhoneRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;

class ModelPhoneController extends Controller
{
    #[Route('/modelPhone', name: 'app_model_phone')]
    public function index(SessionInterface $session, ModelPhoneRepository $modelPhoneRepository): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            return $this->render('model_phone/index.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Lista de Modelos',
                'session'=> $this->sessionDTO,
                'object'=> 'modelos',
                'list'=> $modelPhoneRepository->findBy([], ['title' => 'ASC'])
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

    #[Route('/phone/viewCreate', name: 'app_phone_view')]
    public function viewCreate( SessionInterface $session): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            return $this->render('model_phone/create.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Criar Modelo',
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

    #[Route('/phone/persistCreate', name: 'app_phone_persist')]
    public function persistCreate(Request $request, SessionInterface $session,
                                  EntityManagerInterface $entityManager, ModelPhoneRepository $modelPhoneRepository): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $desc = $request->request->get("desc")??'';

            if(empty($desc)) {
                return $this->render('model_phone/create.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title'=> 'Criar Modelo',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => "Descricao nao pode ser vazia",
                    'desc'=> $desc,
                    'session'=> $this->sessionDTO,
                ]);
            }

            $existing = $modelPhoneRepository->findOneBy(['title' => $desc]);

            if ($existing) {
                return $this->render('model_phone/create.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title' => 'Criar Modelo',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => "Esta modelo já está cadastrada.",
                    'desc'=> $desc,
                    'session'=> $this->sessionDTO,
                ]);
            }


            $object = new ModelPhone();

            $object->setTitle($desc);
            $object->setCreated(new \DateTime());

            $entityManager->persist($object);
            $entityManager->flush();

            if(!is_null($object->getIdModelPhone())) {
                return $this->redirectToRoute('app_model_phone');
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

    #[Route('/phone/viewEdit/{id}', name: 'app_phone_view_edit')]
    public function capacityEdit(Request $request, int $id, SessionInterface $session,CapacityRepository $capacityRepository): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $object  = $capacityRepository->find($id);

            if(!$object) {
                return $this->redirectToRoute('app_model_phone');
            }

            return $this->render('model_phone/edit.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Editar Modelo',
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

    #[Route('/phone/persistEdit/{id}', name: 'app_phone_persist_edit')]
    public function persistEdit(Request $request, int $id, SessionInterface $session,
                                CapacityRepository $capacityRepository, EntityManagerInterface $entityManager): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $object  = $capacityRepository->find($id);

            if(!$object) {
                return $this->redirectToRoute('app_model_phone');
            }

            $desc = $request->request->get("desc")??'';

            if(empty($desc)) {
                return $this->render('model_phone/editar.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title'=> 'Editar Modelo',
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

            return $this->redirectToRoute('model_phone');

        }catch (\Exception $e) {
            return $this->render('capacity/editar.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Editar Modelo',
                'error' => true,
                'type_error' => "Error",
                'message_error' => $e->getMessage(),
                'color'=> $color??null,
                'session'=> $this->sessionDTO,
            ]);
        }
    }
}
