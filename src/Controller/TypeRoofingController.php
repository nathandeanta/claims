<?php

namespace App\Controller;

use App\Entity\TypeRoofing;
use App\Repository\TypeRoofingRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
class TypeRoofingController extends Controller
{
    #[Route('/typeRoofing', name: 'app_type_roofing')]
    public function index(SessionInterface $session, TypeRoofingRepository $typeRoofingRepository): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            return $this->render('type_roofing/index.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Lista de Coberturas',
                'session'=> $this->sessionDTO,
                'object'=> 'Coberturas',
                'list'=> $typeRoofingRepository->findBy([], ['title' => 'ASC'])
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

    #[Route('/typeRoofing/viewCreate', name: 'app_type_roofing_view')]
    public function viewCreate( SessionInterface $session): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            return $this->render('type_roofing/create.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Criar cobertura',
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

    #[Route('/typeRoofing/persistCreate', name: 'app_type_roofing_persist')]
    public function persistCreate(Request $request, SessionInterface $session,
                                  EntityManagerInterface $entityManager, TypeRoofingRepository $typeRoofingRepository): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $desc = $request->request->get("desc")??'';

            if(empty($desc)) {
                return $this->render('type_roofing/create.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title'=> 'Criar Cobertura',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => "Descricao nao pode ser vazia",
                    'desc'=> $desc,
                    'session'=> $this->sessionDTO,
                ]);
            }

            $existing = $typeRoofingRepository->findOneBy(['title' => $desc]);

            if ($existing) {
                return $this->render('type_roofing/create.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title' => 'Criar Cobertura',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => "Esta modelo já está cadastrada.",
                    'desc'=> $desc,
                    'session'=> $this->sessionDTO,
                ]);
            }


            $object = new TypeRoofing();

            $object->setTitle($desc);
            $object->setCreated(new \DateTime());

            $entityManager->persist($object);
            $entityManager->flush();

            if(!is_null($object->getIdTypeRoofing())) {
                return $this->redirectToRoute('app_type_roofing');
            }

            return $this->render('type_roofing/create.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Criar Cobertura',
                'error' => true,
                'type_error' => "Error",
                'message_error' => "Erro ao cadastrar a Cobertura",
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

    #[Route('/typeRoofing/viewEdit/{id}', name: 'app_type_roofing_view_edit')]
    public function capacityEdit(Request $request, int $id, SessionInterface $session,TypeRoofingRepository $typeRoofingRepository): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $object  = $typeRoofingRepository->find($id);

            if(!$object) {
                return $this->redirectToRoute('app_type_roofing');
            }

            return $this->render('type_roofing/edit.html.twig', [
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

    #[Route('/typeRoofing/persistEdit/{id}', name: 'app_type_roofing_persist_edit')]
    public function persistEdit(Request $request, int $id, SessionInterface $session,
                                TypeRoofingRepository $typeRoofingRepository, EntityManagerInterface $entityManager): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $object  = $typeRoofingRepository->find($id);

            if(!$object) {
                return $this->redirectToRoute('app_type_roofing');
            }

            $desc = $request->request->get("desc")??'';

            if(empty($desc)) {
                return $this->render('type_roofing/editar.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title'=> 'Editar cobertura',
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

            return $this->redirectToRoute('app_type_roofing');

        }catch (\Exception $e) {
            return $this->render('type_roofing/editar.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Editar Cobertura',
                'error' => true,
                'type_error' => "Error",
                'message_error' => $e->getMessage(),
                'color'=> $color??null,
                'session'=> $this->sessionDTO,
            ]);
        }
    }
}
