<?php

namespace App\Controller;

use App\Entity\TypeRoofing;
use App\Repository\TypeRoofingRepository;
use App\Service\MarchineLearningService;
use Exception;
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

//app_marchine_view_create
    #[Route('/marchine/create', name: 'app_marchine_view_create',methods:["GET"])]
    public function marchineCreate(Request $request, SessionInterface $session, TypeRoofingRepository $typeRoofingRepository): Response
    {
        try {

            if (($valid = $this->validSession($session)) === false) {
                return $this->render('marchine_learning/index.html.twig');
            }

            return $this->render('marchine_learning/create.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Create Aprendizado',
                'session'=> $this->sessionDTO,
                'types'=> $typeRoofingRepository->findAll()
            ]);

        }catch (Exception $e) {
            return $this->render('index/index.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Create Aprendizado',
                'error' => true,
                'type_error' => "Error",
                'message_error' => "Error ".$e->getMessage() ."file".$e->getFile()
            ]);
        }
    }

    #[Route('/marchine/createPersist', name: 'app_marchine_view_create_persist', methods:["POST","GET"])]
    public function marchinePersist(Request $request, SessionInterface $session, TypeRoofingRepository $typeRoofingRepository): Response
    {
        try {

            if (($valid = $this->validSession($session)) === false) {
                return $this->render('index/index.html.twig');
            }

            if ($request->isMethod('GET')) {
                return $this->redirectToRoute('app_marchine_learning');
            }

            $type = $request->request->get("type")??'';
            $desc = $request->request->get("desc")??'';


            if(empty($type) or empty($desc)) {
                return $this->render('marchine_learning/create.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title'=> 'Create Aprendizado',
                    'error' => true,
                    'type_error' => "Error",
                    'session'=> $this->sessionDTO,
                    'types'=> $typeRoofingRepository->findAll(),
                    'message_error' => ":campos nao podem ser vazios",
                    'type'=> $type,
                    'desc'=> $desc
                ]);
            }

            $marchineService = new MarchineLearningService();
            $responde = $marchineService->createLearning($type, $desc);

            if(isset($responde["status"]) && $responde["status"] == false) {
                return $this->render('marchine_learning/create.html.twig', [
                    'path' => $this->getPathEnv(),
                    'session'=> $this->sessionDTO,
                    'title'=> 'Create Aprendizado',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => $responde["error"],
                    'types'=> $typeRoofingRepository->findAll(),
                    'type'=> $type,
                    'desc'=> $desc
                ]);
            }

            return $this->redirectToRoute('app_marchine_learning');


        }catch (Exception $e) {
            return $this->render('marchine_learning/create.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Create Aprendizado',
                'error' => true,
                'type_error' => "Error",
                'message_error' => "Error ".$e->getMessage() ."file".$e->getFile(),
                'types'=> $typeRoofingRepository->findAll(),
                'session'=> $this->sessionDTO??null,
                'type'=> $type??null,
                'desc'=> $desc??null
            ]);
        }
    }

    #[Route('/marchine/details/{id}', name: 'app_marchine_edit',methods:["GET"])]
    public function marchineDetails(Request $request,int $id, SessionInterface $session, TypeRoofingRepository $typeRoofingRepository): Response
    {
        try {

            if (($valid = $this->validSession($session)) === false) {
                return $this->render('marchine_learning/index.html.twig');
            }

            $marchineService = new MarchineLearningService();
            $response = $marchineService->details($id);

            if(isset($response["status"]) && $response["status"] == false) {
                return $this->redirectToRoute('app_marchine_learning');
            }

            return $this->render('marchine_learning/edit.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Editar Aprendizado',
                'session'=> $this->sessionDTO,
                'types'=> $typeRoofingRepository->findAll(),
                'learning'=> $response["theft_details"]??null
            ]);

        }catch (Exception $e) {
            return $this->redirectToRoute('app_marchine_learning');
        }
    }

    //delete_marchine
    #[Route('/delete/marchine/{id}', name: 'delete_marchine',methods:["GET"])]
    public function marchineDelete(Request $request,int $id, SessionInterface $session, TypeRoofingRepository $typeRoofingRepository): Response
    {
        try {

            if (($valid = $this->validSession($session)) === false) {
                return $this->render('marchine_learning/index.html.twig');
            }

            $marchineService = new MarchineLearningService();

            $response = $marchineService->delete($id);
            return $this->redirectToRoute('app_marchine_learning');


        }catch (Exception $e) {
            return $this->render('marchine_learning/index.html.twig');
        }
    }

    #[Route('/marchine/editPersist/{id}', name: 'app_marchine_view_edit_persist', methods:["POST","GET"])]
    public function marchinePersistEdit(Request $request,int $id, SessionInterface $session, TypeRoofingRepository $typeRoofingRepository): Response
    {
        try {

            if (($valid = $this->validSession($session)) === false) {
                return $this->render('index/index.html.twig');
            }

            if ($request->isMethod('GET')) {
                return $this->redirectToRoute('app_marchine_learning');
            }

            $type = $request->request->get("type")??'';
            $desc = $request->request->get("desc")??'';

            $dados["id"] = $id;
            $dados["desciption"] = $desc;
            $dados["type"] = $type;

            if(empty($type) or empty($desc)) {

                return $this->render('marchine_learning/edit.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title'=> 'Editar Aprendizado',
                    'error' => true,
                    'type_error' => "Error",
                    'session'=> $this->sessionDTO,
                    'types'=> $typeRoofingRepository->findAll(),
                    'message_error' => ":campos nao podem ser vazios",
                    'learning'=> $dados
                ]);
            }

            $marchineService = new MarchineLearningService();
            $response = $marchineService->editLearning($type, $desc, $id);


            if(isset($response["status"]) && $response["status"] == false) {

                return $this->render('marchine_learning/edit.html.twig', [
                    'path' => $this->getPathEnv(),
                    'session'=> $this->sessionDTO,
                    'title'=> 'Editar Aprendizado',
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => $response["error"],
                    'types'=> $typeRoofingRepository->findAll(),
                    'learning'=> $dados
                ]);
            }

            return $this->redirectToRoute('app_marchine_learning');


        }catch (Exception $e) {
            return $this->render('marchine_learning/edit.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Editar Aprendizado',
                'error' => true,
                'type_error' => "Error",
                'message_error' => "Error ".$e->getMessage() ."file".$e->getFile(),
                'types'=> $typeRoofingRepository->findAll(),
                'session'=> $this->sessionDTO??null,
                'type'=> $type??null,
                'desc'=> $desc??null
            ]);
        }
    }

    #[Route('/test', name: 'app_test', methods:["GET", "POST"])]
    public function appTest(Request $request, SessionInterface $session)
    {
        try {

            if (($valid = $this->validSession($session)) === false) {
                return $this->render('index/index.html.twig');
            }

            if ($request->isMethod('GET')) {
                return $this->render('marchine_learning/test.html.twig', [
                    'path' => $this->getPathEnv(),
                    'title'=> 'Test para Marchine Learning',
                    'session'=> $this->sessionDTO,
                ]);
            }

            $desc = $request->request->get("desc")??'';

            if(empty($desc)) {
                return $this->json([
                    'status' => false,
                    'message' => "desc nao pode ser vazio"
                ]);

            }

              $marchineService = new MarchineLearningService();

               $response =  $marchineService->test($desc);

                if(isset($response["status"]) && $response["status"] == false) {

                    return $this->json( [
                        'status' => false,
                        'message' => $response["error"]
                    ]);
                }

                if(!isset($response["prediction"])) {
                    return $this->json( [
                        'status' => false,
                        'message' => "error no prediction"
                    ]);
                }

            return $this->json( [
                'status' => true,
                'value' =>$response["prediction"]
            ]);

        }catch (Exception $e) {
            return $this->render('marchine_learning/test.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Test para Marchine Learning',
                'error' => true,
                'type_error' => "Error",
                'message_error' => "Error ".$e->getMessage() ."file".$e->getFile()
            ]);
        }
    }
}
