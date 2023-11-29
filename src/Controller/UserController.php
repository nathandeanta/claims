<?php

namespace App\Controller;


use App\Entity\User;
use App\Helper\Util;
use App\Repository\TheftRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{

    #[Route('/login', name: 'app_user_login')]
    public function index(Request $request, UserRepository $userRepository,SessionInterface $session): Response
    {
        try {

            if ($request->isMethod('POST')) {

                $email = $request->request->get("email");
                $password = $request->request->get("password");

                if (empty($email) or empty($password)) {
                    return $this->render('index/index.html.twig', [
                        'error' => true,
                        'type_error' => "Error",
                        'message_error' => "Login invalid",
                        'path' => $this->getPathEnv(),
                    ]);
                }

                $user = $userRepository->findOneBy([
                    'email' => $email,
                    'password' => md5($password),
                    'active' => 1,
                ]);

                if ($user) {

                    $session->start();

                    $session->set("client_id", $user->getIdUser());
                    $session->set("user_password", $user->getPassword());
                    $session->set("client_name", $user->getName());
                    $session->set("client_email", $user->getEmail());
                    $session->set("user_admin", $user->getAdmin());
                    $session->set("user_position", $user->getPosition());
                    $session->set("user_avatar", $user->getAvatar()??"uploads/avatars/default-user.jpeg");

                    return $this->redirectToRoute('app_dashboard');

                }

                return $this->render('index/index.html.twig', [
                    'error' => true,
                    'type_error' => "Error",
                    'message_error' => ": Login invalid",
                    'path' => $this->getPathEnv(),
                    'title'=> 'Login',
                    'email'=> $email
                ]);

            }

            return $this->render('index/index.html.twig', [
                'controller_name' => 'IndexController',
                'path' => $this->getPathEnv(),
            ]);

        }catch (\Exception $e) {
            return $this->render('index/index.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> ' Login',
                'error' => true,
                'type_error' => "Error",
                'message_error' => "Error ".$e->getMessage(),
            ]);
        }
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(Request $request, SessionInterface $session, TheftRepository $theftRepository): Response
    {
        try {

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            return $this->render('index/dashboard.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> 'Dashboard',
                'session'=> $this->sessionDTO,
                'aprovado'=>$theftRepository->countTheftsbyStatus("Aprovado"),
                "reprovado"=> $theftRepository->countTheftsbyStatus("Negado"),
                'ana'=>$theftRepository->countTheftsWithNullValues()
            ]);

        }catch (Exception $e) {
            return $this->render('index/index.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> ' Login Cliente',
                'error' => true,
                'type_error' => "Error",
                'message_error' => "Error ".$e->getMessage() ."file".$e->getFile()
            ]);
        }
    }

    #[Route('/logout', name: 'app_user_logout')]
    public function logout(Request $request, SessionInterface $session): Response
    {
        $session->invalidate();
        return $this->redirectToRoute('app_user_login');
    }

    #[Route('/user', name: 'app_user')]
    public function listUSer(SessionInterface $session, UserRepository $userRepository): Response
    {
        if(($valid = $this->validSession($session)) === false) {
            return $this->redirectToRoute('app_user_login');
        }

        if($this->sessionDTO->getAdmin() != "1") {
            return $this->render(
                'user/nopermission.html.twig',
                ['title'=> 'Not permitted',
                    'nopermission'=> 'You do not have permissions to access this area, contact the administrator',
                    'session'=> $this->sessionDTO,
                    'path' => $this->getPathEnv(),],
            );
        }

        return $this->render('user/index.html.twig', [
            'session'=> $this->sessionDTO,
            'users'=>  $userRepository->findAll(),
            'title'=> "Lista de usuários",
            'path' => $this->getPathEnv(),
        ]);
    }

    #[Route('/user/createView', name: 'app_user_view')]
    public function createView(SessionInterface $session,
                               UserRepository $userRepository): Response
    {
        if(($valid = $this->validSession($session)) === false) {
            return $this->redirectToRoute('app_user_login');
        }

        if($this->sessionDTO->getAdmin() != "1") {
            return $this->render(
                'user/nopermission.html.twig',
                ['title'=> 'Not permitted',
                    'nopermission'=> 'You do not have permissions to access this area, contact the administrator',
                    'session'=> $this->sessionDTO,
                    'path' => $this->getPathEnv(),],
            );
        }

        return $this->render('user/create.html.twig', [
            'session'=> $this->sessionDTO,
            'title'=> "Create User",
            'path' => $this->getPathEnv(),
        ]);
    }

    #[Route('/user/{id_user}', name: 'app_user_view_edit', methods: ["GET"])]
    public function createViewEdit( int $id_user, SessionInterface $session,
                                    UserRepository $userRepository): Response
    {
        if(($valid = $this->validSession($session)) === false) {
            return $this->redirectToRoute('app_user_login');
        }

        if($this->sessionDTO->getAdmin() != "1") {
            return $this->render(
                'user/nopermission.html.twig',
                ['title'=> 'Not permitted',
                    'nopermission'=> 'You do not have permissions to access this area, contact the administrator',
                    'session'=> $this->sessionDTO,
                    'path' => $this->getPathEnv(),],
            );
        }

        $user = $userRepository->find($id_user);

        if(!$user) {
            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/edit.html.twig', [
            'session'=> $this->sessionDTO,
            'title'=> "Edit User",
            'user'=> $user,
            'path' => $this->getPathEnv(),
        ]);
    }

    #[Route('/user/create', name: 'app_user_create')]
    public function create(
        SessionInterface $session,
        EntityManagerInterface $entityManager, Request $request, UserRepository $userRepository): Response
    {
        if(($valid = $this->validSession($session)) === false) {
            return $this->redirectToRoute('app_user_login');
        }

        if($this->sessionDTO->getAdmin() != "1") {
            return $this->render(
                'user/nopermission.html.twig',
                ['title'=> 'Not permitted',
                    'nopermission'=> 'You do not have permissions to access this area, contact the administrator',
                    'session'=> $this->sessionDTO,
                    'path' => $this->getPathEnv(),],
            );
        }


        if($request->isMethod("POST")) {

            $first_name = $request->request->get("first_name");
            $email = $request->request->get("email");
            $password = $request->request->get("password");
            $admin = $request->request->get("admin");
            $position = $request->request->get("position");
            $file = $request->files->get("avatar");

            $emailValid = $userRepository->findOneBy(["email"=> $email]);

            if($emailValid) {
                return $this->render('user/create.html.twig', [
                    'error' => true,
                    'type_error'=> "Error",
                    'message_error'=> "Email exist ".$emailValid->getEmail(),
                    'title'=> "Criar usuário",
                    'session'=> $this->sessionDTO,
                    'path' => $this->getPathEnv(),
                ]);
            }

            $user = new User();

            $user->setName($first_name);
            $user->setEmail($email);
            $user->setPassword(md5($password));
            $user->setAdmin(($admin));
            $user->setPosition($position);
            $user->setActive("1");

            if ($file) {

                $date = date("dmYHis");

                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename .$date. '.' . $file->guessExtension();

                $path = "uploads/avatars/".$newFilename;

                try {
                    $file->move(
                        $this->getParameter('your_upload_directory') . "/avatars",
                        $newFilename
                    );
                }catch (\Exception $e) {

                    dd($e->getMessage());
                }

                $user->setAvatar($path);

            }


            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user');

        }


        return $this->render('user/create.html.twig', [
            'session'=> $this->sessionDTO,
            'title'=> "Create User",
            'path' => $this->getPathEnv(),
        ]);
    }

    #[Route('/user/{id_user}', name: 'app_user_view_update', methods: ["POST"])]
    public function updateViewEdit( int $id_user, SessionInterface $session,
                                    UserRepository $userRepository,
                                    Request $request,
                                    EntityManagerInterface $entityManager): Response
    {
        if(($valid = $this->validSession($session)) === false) {
            return $this->redirectToRoute('app_user_login');
        }

        if($this->sessionDTO->getAdmin() != "1") {
            return $this->render(
                'user/nopermission.html.twig',
                ['title'=> 'Not permitted',
                    'nopermission'=> 'You do not have permissions to access this area, contact the administrator',
                    'session'=> $this->sessionDTO,
                    'path' => $this->getPathEnv(),],
            );
        }

        $user = $userRepository->find($id_user);

        if($request->isMethod("POST")) {
            $first_name = $request->request->get("first_name");
            $last_name = $request->request->get("last_name");
            $email = $request->request->get("email");
            $password = $request->request->get("password");
            $admin = $request->request->get("admin");
            $position = $request->request->get("position");
            $active = $request->request->get("active");
            $file = $request->files->get("avatar");

            if(!$user) {
                return $this->redirectToRoute('app_user');
            }

            $user->setName($first_name);
            $user->setEmail($email);
            $user->setAdmin($admin);
            $user->setPosition($position);
            $user->setActive($active);

            if(!Util::validMd5($password)) {
                $user->setPassword(md5($password));
            }

            if ($file) {

                $date = date("dmYHis");

                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename .$date. '.' . $file->guessExtension();

                $path = "uploads/avatars/".$newFilename;

                try {
                    $file->move(
                        $this->getParameter('your_upload_directory') . "/avatars",
                        $newFilename
                    );
                }catch (\Exception $e) {

                    dd($e->getMessage());
                }

                $user->setAvatar($path);

            }

            try {

                $entityManager->persist($user);
                $entityManager->flush();
            }catch (UniqueConstraintViolationException $e) {
                return $this->render('user/edit.html.twig', [
                    'error' => true,
                    'type_error'=> "Error",
                    'message_error'=> "email already exists ".$email,
                    'title'=> "Edit User",
                    'session'=> $this->sessionDTO,
                    'user'=> $user,
                    'path' => $this->getPathEnv(),
                ]);
            }

            return $this->redirectToRoute('app_user');

        }

        return $this->render('user/edit.html.twig', [
            'session'=> $this->sessionDTO,
            'title'=> "Edit User",
            'user'=> $user,
            'path' => $this->getPathEnv(),
        ]);
    }


    #[Route('/delete_user/{id}', name: 'delete_user')]
    public function delete(Request $request,int $id, SessionInterface $session, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        try {

            if (($valid = $this->validSession($session)) === false) {
                return $this->redirectToRoute('app_user_login');
            }

            $user = $userRepository->find($id);

            if(!$user) {
                return $this->redirectToRoute('app_user_view_edit', ["id_user"=> (int) $id]);
            }

            $entityManager->remove($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user');

        }catch (Exception $e) {
            return $this->render('index/index.html.twig', [
                'path' => $this->getPathEnv(),
                'title'=> ' Login Cliente',
                'error' => true,
                'type_error' => "Error",
                'message_error' => "Error ".$e->getMessage() ."file".$e->getFile(),
                'path' => $this->getPathEnv(),
            ]);
        }
    }



}
