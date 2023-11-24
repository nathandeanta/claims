<?php

namespace App\Controller;

use App\Entity\CashFlow;
use App\Entity\TypeFlow;
use App\Helper\ConvertDatesTime;
use App\Repository\CashFlowRepository;
use App\Repository\TypeFlowRepository;
use App\Repository\UserRepository;
use App\Service\ExcelService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CashFlowController extends Controller
{
    #[Route('/cashFlow/create', name: 'app_cash_flow_create', methods:["POST","GET"])]
    public function index(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        if(($valid = $this->validSession($session)) === false) {
            return $this->render('index/index.html.twig');
        }

        if ($request->isMethod('POST')) {

            $description = $request->request->get('description');
            $value = $request->request->get('value');
            $type = $request->request->get('type');
            $date = $request->request->get('date');
            $category = $request->request->get("category");
            $bank = $request->request->get("bank");
            $currency = $request->request->get("currency");

            $cashFlow = new CashFlow();
            $cashFlow->setValue($value);
            $cashFlow->setType($type);
            $cashFlow->setDate(new \DateTime($date));
            $cashFlow->setCreatedAt(new \DateTime());
            $cashFlow->setDescription($description);
            $cashFlow->setBank($bank);
            $cashFlow->setCurrency($currency);

            if(!empty($category)) {
                $categoryEntity = $entityManager->getRepository(TypeFlow::class)->find($category);
                $cashFlow->setTypeFlow($categoryEntity);
            }

            $entityManager->persist($cashFlow);
            $entityManager->flush();

            return $this->redirectToRoute('app_cash_flow_all');
        }

        return $this->render('cash_flow/create-view.html.twig',['session'=> $this->sessionDTO]);

    }

    #[Route('/cashFlow/update/{id}', name: 'app_cash_flow_update', methods:["POST","GET"])]
    public function update(Request $request, EntityManagerInterface $entityManager,int $id,
                           CashFlowRepository $cashFlowRepository,
                           TypeFlowRepository $typeFlowRepository,
                    SessionInterface $session): Response
    {
        if(($valid = $this->validSession($session)) === false) {
            return $this->render('index/index.html.twig');
        }

        if ($request->isMethod('POST')) {

            $description = $request->request->get('description');
            $value = $request->request->get('value');
            $type = $request->request->get('type');
            $date = $request->request->get('date');
            $category = $request->request->get("category");


            $cashFlow = $cashFlowRepository->find($id);

            if (!$cashFlow) {
                throw $this->createNotFoundException('not found');
            }

            if(!empty($category)) {
                $typeFlow = $typeFlowRepository->find($category);

                if(!$typeFlow) {
                    throw $this->createNotFoundException('type error');
                }

                $cashFlow->setTypeFlow($typeFlow);
            }

            $cashFlow->setValue($value);
            $cashFlow->setType($type);
            $cashFlow->setDate(new \DateTime($date));
            $cashFlow->setCreatedAt(new \DateTime());
            $cashFlow->setDescription($description);

            $entityManager->persist($cashFlow);
            $entityManager->flush();

            return $this->redirectToRoute('app_cash_flow_all');
        }

        return $this->redirectToRoute('edit_cash_flow', ['id' => $id]);

    }

    #[Route('/cashFlow/view/create', name: 'create_cash_flow', methods:["GET"])]
    public function createView(SessionInterface $session,
                               TypeFlowRepository $typeFlowRepository,
                               UserRepository $userRepository): Response
    {
        if(($valid = $this->validSession($session)) === false) {
            return $this->render('index/index.html.twig');
        }

        $user = $userRepository->find($this->sessionDTO->getIdUser());
        $categories = $typeFlowRepository->findBy(['user' => $user]);

        return $this->render('cash_flow/create-view.html.twig',
            [
                'session'=> $this->sessionDTO,
                'categories'=> $categories]);
    }

    //app_cash_flow_uplaod_bank
    #[Route('/cashFlow/view/upload-bank', name: 'app_cash_flow_uplaod_bank', methods:["GET"])]
    public function uploadBankView(SessionInterface $session): Response
    {
        if(($valid = $this->validSession($session)) === false) {
            return $this->render('index/index.html.twig');
        }

        return $this->render('cash_flow/upload-view.html.twig',['session'=> $this->sessionDTO]);
    }

    //app_cash_flow_filter

    #[Route('/cashFlow/all/filter', name: 'app_cash_flow_filter', methods:["POST", "GET"])]
    public function getAllFilter(Request $request, CashFlowRepository $cashFlowRepository,
                                 SessionInterface $session, UserRepository $userRepository, TypeFlowRepository $typeFlowRepository): Response
    {
        if(($valid = $this->validSession($session)) === false) {
            return $this->render('index/index.html.twig');
        }

        if ($request->isMethod('POST')) {

            $bank = $request->request->get("bank");
            $search = $request->request->get("search");
            $start = $request->request->get("start");
            $end = $request->request->get("end") ?? date("Y-m-d");
            $status = $request->request->get("status");
            $currency = $request->request->get("currency")??"EUR";
            $category = $request->request->get("category");


            $list = $cashFlowRepository->filter($bank, $search, $start, $end, $status, $currency,$category);

            $currencyTo = "BRL";

            $total = $cashFlowRepository->getTotalAmountForBankAndCurrencyFilter($bank, $currencyTo, $search, $start, $end, $status, $currency,$category);

            if($currency == "EUR") {
                $route = 'cash_flow/index.html.twig';
            }elseif($currency == "BRL"){
                $route = 'cash_flow/brazil/index.html.twig';
            }

            $user = $userRepository->find($this->sessionDTO->getIdUser());
            $categories = $typeFlowRepository->findBy(['user' => $user]);
            return $this->render($route, [
                'cashFlows' => $list,
                'total_real' => $total[0]["total"] ?? 0,
                "bank"=> $bank,
                "search"=>$search,
                "start"=> $start,
                "end"=>$end,
                "status"=>$status,
                'session'=> $this->sessionDTO,
                'category'=>$category,
                'categories'=> $categories
            ]);

        }

        return $this->redirectToRoute('app_dashboard');

    }

    #[Route('/cashFlow/all/brazil', name: 'app_cash_flow_all_brazil', methods:["GET"])]
    public function getAllBrazil(Request $request, CashFlowRepository $cashFlowRepository, SessionInterface $session): Response
    {
        if(($valid = $this->validSession($session)) === false) {
            return $this->render('index/index.html.twig');
        }

        $currency = "BRL";

        $list = $cashFlowRepository->findBy(['currency' => $currency], ['date' => 'DESC', 'id_cash_flow' => 'DESC']);

        $bank = "Wise";
        $currencyTo = "BRL";

        $total = $cashFlowRepository->getTotalAmountForBankAndCurrency($bank, $currencyTo, $currency);

        return $this->render('cash_flow/brazil/index.html.twig', [
            'cashFlows' => $list,
            'total_real'=> $total,
            'session'=> $this->sessionDTO
        ]);
    }


    #[Route('/cashFlow/all', name: 'app_cash_flow_all', methods:["GET"])]
    public function getAll(Request $request, CashFlowRepository $cashFlowRepository,
                           SessionInterface $session,
                           TypeFlowRepository $typeFlowRepository, UserRepository $userRepository): Response
    {
        if(($valid = $this->validSession($session)) === false) {
            return $this->render('index/index.html.twig');
        }

        $currency = "EUR";

        $list = $cashFlowRepository->findBy(['currency' => $currency], ['date' => 'DESC', 'id_cash_flow' => 'DESC']);

        $bank = "Wise";
        $currencyTo = "BRL";

        $user = $userRepository->find($this->sessionDTO->getIdUser());
        $categories = $typeFlowRepository->findBy(['user' => $user]);

        $total = $cashFlowRepository->getTotalAmountForBankAndCurrency($bank, $currencyTo, $currency);

        return $this->render('cash_flow/index.html.twig', [
            'cashFlows' => $list,
            'total_real'=> $total,
            'session'=> $this->sessionDTO,
            'categories'=> $categories
        ]);
    }

    #[Route('/cashFlow/edit/{id}', name: 'edit_cash_flow', methods:["GET"])]
    public function editCashFlow(Request $request, CashFlowRepository $cashFlowRepository,
                                 int $id, SessionInterface $session,
                                 TypeFlowRepository $typeFlowRepository, UserRepository $userRepository): Response
    {
        if(($valid = $this->validSession($session)) === false) {
            return $this->render('index/index.html.twig');
        }

        $cashFlow = $cashFlowRepository->find($id);

        $user = $userRepository->find($this->sessionDTO->getIdUser());

        $categories = $typeFlowRepository->findBy(['user' => $user]);

        if (!$cashFlow) {
            throw $this->createNotFoundException('not found');
        }

        return $this->render('cash_flow/edit-view.html.twig', [
            'cashFlow' => $cashFlow,'session'=> $this->sessionDTO,
            'categories'=> $categories
        ]);
    }

    #[Route('/cashFlow/delete/{id}', name: 'delete_cash_flow', methods:["GET"])]
    public function deleteCashFlow(Request $request, CashFlowRepository $cashFlowRepository,
                                   int $id, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        if(($valid = $this->validSession($session)) === false) {
            return $this->render('index/index.html.twig');
        }

        $cashFlow = $cashFlowRepository->find($id);

        if (!$cashFlow) {
            throw $this->createNotFoundException('not found');
        }

        $entityManager->remove($cashFlow);
        $entityManager->flush();

        return $this->redirectToRoute('app_cash_flow_all');
    }

    /**
     * @throws \Exception
     */
    #[Route('/cashFlow/upload', name: 'upload_cash_flow_csv', methods:["POST"])]
    public function create(Request $request,EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        if(($valid = $this->validSession($session)) === false) {
            return $this->render('index/index.html.twig');
        }

        if ($request->isMethod('POST')) {

            $uploadedFile = $request->files->get('upload');
            $bank = $request->request->get("bank");

            if ($uploadedFile) {

                $excelService = new ExcelService();

               $lines = $excelService->readfile($uploadedFile->getRealPath());

               if(sizeof($lines) > 0 && $bank == "Revolut" && sizeof($lines[0]) == 10) {

                   $start = false;
                   foreach ($lines as $value) {

                       if(!$start) {
                           $start = true;
                           continue;
                       }

                       $negative = $excelService->isNegativeNumber($value[5]);

                       if($negative) {
                           $status= "Out";
                       }else{
                           $status = "Deposit";
                       }

                       $cashFlow = new CashFlow();
                       $cashFlow->setValue($value[5]);
                       $cashFlow->setType($status);
                       $cashFlow->setBank($bank);
                       $cashFlow->setCurrency($value[7]);
                       $cashFlow->setTypeTransactiion($value[0]);
                       $date = \DateTime::createFromFormat('Y-m-d H:i:s', $value[2]);
                       $cashFlow->setDate($date);
                       $cashFlow->setDescription($value[4]);
                       $cashFlow->setCreatedAt(new \DateTime());

                       $existingCashFlow = $entityManager->getRepository(CashFlow::class)->findOneBy([
                           'value' => $cashFlow->getValue(),
                           'type' => $cashFlow->getType(),
                           'bank' => $cashFlow->getBank(),
                           'currency' => $cashFlow->getCurrency(),
                           'date' => $cashFlow->getDate(),
                           'description' => $cashFlow->getDescription(),
                           'type_transactiion' => $cashFlow->getTypeTransactiion()
                       ]);

                       if ($existingCashFlow === null) {

                           $entityManager->persist($cashFlow);
                           $entityManager->flush();

                       }

                       unset($cashFlow);
                       unset($date);

                   }

                   return $this->redirectToRoute('app_cash_flow_all');

               }

                if(sizeof($lines) > 0 && $bank == "Wise"  && sizeof($lines[0]) == 19) {

                    $start = false;

                    foreach ($lines as $value) {
                        if(!$start) {
                            $start = true;
                            continue;
                        }

                        $negative = $excelService->isNegativeNumber($value[2]);

                        if($negative) {
                            $status= "Out";
                        }else{
                            $status = "Deposit";
                        }

                        $cashFlow = new CashFlow();
                        $cashFlow->setValue($value[2]);
                        $cashFlow->setType($status);
                        $cashFlow->setBank($bank);
                        $cashFlow->setCurrency($value[3]);
                        $cashFlow->setTypeTransactiion($value[0]);


                        list($day, $month, $year) = explode('-', $value[1]);

                        if (strlen($year) === 4) {
                            $formattedDate = "20" . substr($year, 2) . "-$month-$day";

                        }

                        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $formattedDate." 00:00:00");

                        $cashFlow->setDate($date);
                        $cashFlow->setDescription($value[4]);
                        $cashFlow->setCurrencyTo($value[8]);
                        $cashFlow->setCurrentConvert($value[9]);
                        $cashFlow->setMerchant($value[13]);
                        $cashFlow->setCreatedAt(new \DateTime());

                        $existingCashFlow = $entityManager->getRepository(CashFlow::class)->findOneBy([
                            'value' => $cashFlow->getValue(),
                            'type' => $cashFlow->getType(),
                            'bank' => $cashFlow->getBank(),
                            'currency' => $cashFlow->getCurrency(),
                            'date' => $cashFlow->getDate(),
                            'description' => $cashFlow->getDescription(),
                            'type_transactiion' => $cashFlow->getTypeTransactiion()
                        ]);


                        if ($existingCashFlow === null) {

                            $entityManager->persist($cashFlow);
                            $entityManager->flush();

                        }

                        unset($cashFlow);
                        unset($date);

                    }

                    return $this->redirectToRoute('app_cash_flow_all');

                }


                if(sizeof($lines) > 0 && $bank == "Nubank" && sizeof($lines[0]) == 4) {

                    $start = false;

                    foreach ($lines as $value) {
                        if(!$start) {
                            $start = true;
                            continue;
                        }

                        $negative = $excelService->isNegativeNumber($value[1]);

                        if($negative) {
                            $status= "Out";
                        }else{
                            $status = "Deposit";
                        }

                        $cashFlow = new CashFlow();
                        $cashFlow->setValue($value[1]);
                        $cashFlow->setType($status);
                        $cashFlow->setBank($bank);
                        $cashFlow->setCurrency('BRL');
                        $cashFlow->setTypeTransactiion($value[2]);

                        list($day, $month, $year) = explode('/', $value[0]);

                        if (strlen($year) === 4) {
                            $formattedDate = "20" . substr($year, 2) . "-$month-$day";

                        }

                        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $formattedDate." 00:00:00");

                        $cashFlow->setDate($date);
                        $cashFlow->setDescription($value[3]);
                        $cashFlow->setCreatedAt(new \DateTime());

                        $existingCashFlow = $entityManager->getRepository(CashFlow::class)->findOneBy([
                            'value' => $cashFlow->getValue(),
                            'type' => $cashFlow->getType(),
                            'bank' => $cashFlow->getBank(),
                            'currency' => $cashFlow->getCurrency(),
                            'date' => $cashFlow->getDate(),
                            'description' => $cashFlow->getDescription(),
                            'type_transactiion' => $cashFlow->getTypeTransactiion()
                        ]);


                        if ($existingCashFlow === null) {

                            $entityManager->persist($cashFlow);
                            $entityManager->flush();

                        }

                        unset($cashFlow);
                        unset($date);

                    }

                    return $this->redirectToRoute('app_cash_flow_all_brazil');

                }

            }
        }

        return $this->render('cash_flow/upload-view.html.twig');
    }


}
