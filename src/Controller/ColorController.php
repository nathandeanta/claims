<?php

namespace App\Controller;

use App\Entity\Color;
use App\Repository\ColorRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;

class ColorController extends Controller
{
    #[Route('/color', name: 'app_color')]
    public function index(Request $request, SessionInterface $session, ColorRepository $colorRepository): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            return $this->render('color/index.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Lista de Cores',
                'session'=> $this->sessionDTO,
                'cores'=> $colorRepository->findBy([], ['title' => 'ASC'])
            ]);

        }catch (\Exception $e) {
            return $this->render('index/index.html.twig');
        }
    }

    #[Route('/color/viewCreate', name: 'app_color_view')]
    public function viewCreate(Request $request, SessionInterface $session): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            return $this->render('color/create.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Criar Cor',
                'session'=> $this->sessionDTO,
            ]);

        }catch (\Exception $e) {
            return $this->render('index/index.html.twig');
        }
    }
    //app_color_create_persist

    #[Route('/color/persistCreate', name: 'app_color_persist')]
    public function persistCreate(Request $request, SessionInterface $session,
                                  EntityManagerInterface $entityManager, ColorRepository $colorRepository): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $desc = $request->request->get("desc")??'';

            if(empty($desc)) {
                return $this->render('color/create.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title'=> 'Criar Color',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => "Descricao nao pode ser vazia",
                    'desc'=> $desc,
                    'session'=> $this->sessionDTO,
                ]);
            }

            $existingColor = $colorRepository->findOneBy(['title' => $desc]);

            if ($existingColor) {
                return $this->render('color/create.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title' => 'Criar Color',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => "Esta cor já está cadastrada.",
                    'desc'=> $desc,
                    'session'=> $this->sessionDTO,
                ]);
            }


            $color = new Color();

            $color->setTitle($desc);
            $color->setCreated(new \DateTime());

            $entityManager->persist($color);
            $entityManager->flush();

            if(!is_null($color->getIdColor())) {
                return $this->redirectToRoute('app_color');
            }

            return $this->render('color/create.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Criar Color',
                'error' => true,
                'type_error' => "Error",
                'message_error' => "Erro ao cadastrar a cor",
                'desc'=> $desc,
                'session'=> $this->sessionDTO,
            ]);

        }catch (\Exception $e) {
            return $this->render('index/index.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Criar Color',
                'error' => true,
                'type_error' => "Error",
                'message_error' => "Error ".$e->getMessage() ."file".$e->getFile(),
                'session'=> $this->sessionDTO,
            ]);
        }
    }

    #[Route('/color/viewEdit/{id}', name: 'app_color_view_edit')]
    public function viewEdit(Request $request, int $id, SessionInterface $session, ColorRepository $colorRepository): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $color  = $colorRepository->find($id);

            if(!$color) {
                return $this->redirectToRoute('app_color');
            }

            return $this->render('color/edit.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Editar Cor',
                'session'=> $this->sessionDTO,
                'color'=> $color
            ]);

        }catch (\Exception $e) {
            dd($e->getMessage());
            return $this->render('index/index.html.twig');
        }
    }

    #[Route('/color/persistEdit/{id}', name: 'app_color_persist_edit')]
    public function persistEdit(Request $request, int $id, SessionInterface $session,
                                ColorRepository $colorRepository, EntityManagerInterface $entityManager): Response
    {
        try{

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $color  = $colorRepository->find($id);

            if(!$color) {
                return $this->redirectToRoute('app_color');
            }

            $desc = $request->request->get("desc")??'';

            if(empty($desc)) {
                return $this->render('color/editar.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title'=> 'Editar Color',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => "Descricao nao pode ser vazia",
                    'color'=> $color,
                    'session'=> $this->sessionDTO,
                ]);
            }

            $color->setTitle($desc);

            $entityManager->merge($color);
            $entityManager->flush();

            return $this->redirectToRoute('app_color');

        }catch (\Exception $e) {
            return $this->render('color/editar.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Editar Color',
                'error' => true,
                'type_error' => "Error",
                'message_error' => "Descricao nao pode ser vazia",
                'color'=> $color??null,
                'session'=> $this->sessionDTO,
            ]);
        }
    }
}
