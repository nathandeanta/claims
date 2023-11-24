<?php

namespace App\Controller;

use App\Entity\Debts;
use App\Entity\User;
use App\Repository\DebtsRepository;
use App\Repository\UserRepository;
use Cassandra\Date;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use setasign\Fpdi\Fpdi;
use Smalot\PdfParser\Parser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DebtsController extends Controller
{
    #[Route('/debts/register', name: 'app_debts_register_view')]
    public function index(): Response
    {
        return $this->render('debts/index.html.twig', [
            'controller_name' => 'DebtsController',
        ]);
    }

    #[Route('/debts/list/filter', name: 'app_debts_filter', methods:["POST", "GET"])]
    public function getAllFilter(Request $request,DebtsRepository $debtsRepository,
                                 SessionInterface $session): Response
    {
        if(($valid = $this->validSession($session)) === false) {
            return $this->render('index/index.html.twig');
        }

        if ($request->isMethod('POST')) {

            $service = $request->request->get("service");
            $search = $request->request->get("search");
            $start = $request->request->get("start");
            $end = $request->request->get("end") ?? date("Y-m-d");
            $type = $request->request->get("type");

            $list = $debtsRepository->filter($service, $search, $start, $end);

            return $this->render('debts/index.html.twig', [
                'debts' => $list,
                "bank"=> $service,
                "search"=>$search,
                "start"=> $start,
                "end"=>$end,
                "type"=>$type,
                "title"=> "List Investments",
                'session'=> $this->sessionDTO
            ]);

        }

        return $this->redirectToRoute('app_debts_list');

    }

    #[Route('/debts/list', name: 'app_debts_list')]
    public function list(SessionInterface $session, DebtsRepository $debtsRepository, UserRepository $userRepository): Response
    {
        if (($valid = $this->validSession($session)) === false) {
            return $this->render('index/index.html.twig');
        }

        $user =  $userRepository->findOneBy(["id_user"=>$this->sessionDTO->getIdUser()]);

        $debts =  $debtsRepository->findBy(["user"=> $user]);


        return $this->render('debts/index.html.twig', [
            'controller_name' => 'DebtsController',
            'session'=> $this->sessionDTO,
            'debts'=> $debts,
            'title'=>'List of Debts'
        ]);
    }

    #[Route('/debts/upload/view', name: 'app_debts_upload_view')]
    public function uploadView(SessionInterface $session): Response
    {
        if (($valid = $this->validSession($session)) === false) {
            return $this->render('index/index.html.twig');
        }
        return $this->render('debts/upload-view.html.twig', [
            'controller_name' => 'DebtsController',
            'title'=>"Upload Debts",
            'session'=> $this->sessionDTO
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/debts/upload', name: 'upload_debts_create', methods:["POST"])]
    public function create(Request $request,
                           EntityManagerInterface $entityManager,
                           SessionInterface $session): Response
    {
        if (($valid = $this->validSession($session)) === false) {
            return $this->render('index/index.html.twig');
        }

        if ($request->isMethod('POST')) {

            $uploadedFile = $request->files->get('upload');

            $parser = new Parser();


            $pdf = $parser->parseFile($uploadedFile->getRealPath());

            $pageCount = count($pdf->getPages());

            $lastPage = $pdf->getPages()[$pageCount - 1];

            $text = $lastPage->getText();

            $pdfLines = explode(PHP_EOL, $text);

            $lines  =[];
            foreach ($pdfLines as $line) {

                $lines[]=  $line;
            }

            $transactions = [];
            $currentTransaction = [];

            foreach ($lines as $line) {

                $line = trim($line);

                if (!empty($line)) {

                    $currentTransaction[] = $line;
                } elseif (!empty($currentTransaction)) {
                    $transactions[] = implode(' ', $currentTransaction);
                    $currentTransaction = [];
                }
            }

            if (!empty($currentTransaction)) {
                $transactions[] = implode(' ', $currentTransaction);
            }

            $arrayTransacao = [];
            $i=0;
            $array = false;
            $sum = 0;
            foreach ($transactions as $transaction) {

                if($this->validarData($transaction)) {

                    $array = true;
                }

                if($array) {

                    if( (strpos($transaction, 'USD') !== false) && $i >0) {
                        continue;
                    }

                    if( (strpos($transaction, 'Pagamento em ') !== false) && $i >0) {

                        $foundPay  =$transaction;
                    }

                    $new[$i++] = $transaction;


                    if($i > 2) {

                        if(isset($foundPay) && $foundPay != $transaction) {
                            $new[2] = "-".$new[2];
                            unset($foundPay);
                        }else{
                            $format = str_replace(",",".",$new[2]);
                            $sum = $sum + $format;
                        }

                        $arrayTransacao[] = $new;
                        $i=0;
                        $array = false;

                    }

                    if((strpos($transaction, "/")) !==false) {
                        $new[3] = $this->getText($transaction);
                    }

                }

            }

            if(sizeof($arrayTransacao)==0) {
                return $this->render('debts/upload.html.twig', [
                    'controller_name' => 'DebtsController',
                    'session'=> $this->sessionDTO,
                    'title'=> "Upload Debts"
                ]);
            }


            $user = $entityManager->getRepository(User::class)->findOneBy(['id_user'=> $this->sessionDTO->getIdUser()]);

            $ui_generate = $this->getUniqueID();

            foreach ($arrayTransacao as $value) {

                $debts =  new Debts();

                $date = \DateTime::createFromFormat('Y-m-d', $this->convertDate($value[0]));
                $debts->setDate($date);

                $debts->setAmount($value[2]);

                $debts->setDescribe($value[1]);
                $debts->setUser($user);
                $debts->setService("Nubank");
                $debts->setTotal($sum);
                $debts->setUiGenerate($ui_generate);

                $date_card = explode("/", $value[3]);

                if(isset($date_card[0])) {
                    $portion = (int) $date_card[0];

                    $debts->setPortion($portion);
                }

                if(isset($date_card[1])) {
                    $number = (int) $date_card[1];
                    $debts->setNumberOfInstallments($number);
                }

                $debts->setCreatedAt(new \DateTime());

                $existing = $entityManager->getRepository(Debts::class)->findOneBy([
                    'amount' => $debts->getAmount(),
                    'date' => $debts->getDate(),
                    'describe' => $debts->getDescribe(),
                    'portion' => $debts->getPortion()
                ]);

                if ($existing === null) {

                    $entityManager->persist($debts);
                    $entityManager->flush();

                }


            }


        }

        return $this->redirectToRoute('app_debts_list');

    }

    private function getUniqueID()
    {
        $prefix = "GRP";
        $timestamp = time();
        return  ($prefix . '-' . $timestamp);
    }

    private function convertDate($dateString)
    {
            $date = DateTime::createFromFormat("d M", $dateString);
            if ($date instanceof DateTime) {
                return $date->format("Y-m-d");
            }

    }

   public  function validarData($data) {
        // Use uma expressão regular para verificar o formato
        if (preg_match('/^\d{2} (JAN|FEV|MAR|ABR|MAI|JUN|JUL|AGO|SET|OUT|NOV|DEZ)$/i', $data)) {
            return true; // A data está no formato válido
        } else {
            return false; // A data não está no formato válido
        }
    }

    public function getText($text)
    {
        $padrao = '/^(.*?) - (\d{1,2}\/\d{1,2})$/';

        if (preg_match($padrao, $text, $matches)) {
            return  $matches[2];

        } else {
            return null;
        }
    }
}
