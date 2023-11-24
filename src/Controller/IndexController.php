<?php

namespace App\Controller;

use App\Service\CurrencyService;
use App\Service\EmailService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    #[Route('/', name: 'app_index')]
    public function index(SessionInterface $session): Response
    {
        if(($valid = $this->validSession($session)) === false) {
            return $this->render('index/index.html.twig');
        }else{
            return $this->redirectToRoute('app_dashboard');
        }
    }

    #[Route('/mail', name: 'app_index_mail')]
    public function mail(MailerInterface $mailer): Response
    {
       $emailService = new EmailService($mailer);

       $emailService->sendMail();

        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(SessionInterface $session): Response
    {
        if(($valid = $this->validSession($session)) === false) {
            return $this->render('index/index.html.twig');
        }

        $currencyService = new CurrencyService();
        $euro =   $currencyService->getCoinToBRL("EUR-BRL");
        $bitcoin =   $currencyService->getCoinToBRL("BTC-BRL");
        $dolar  = $currencyService->getCoinToBRL("USD-BRL");

        return $this->render('index/dashboard.html.twig', [
            'euro_to_brl' => $euro,
            'btc_to_brl' => $bitcoin,
            'usd_to_brl'=> $dolar,
            'session'=>$this->sessionDTO
        ]);
    }
}
