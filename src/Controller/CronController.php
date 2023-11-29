<?php

namespace App\Controller;

use App\Repository\TheftRepository;
use App\Service\MarchineLearningService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class CronController extends AbstractController
{
    #[Route('/cron/ai', name: 'app_cron')]
    public function index(TheftRepository $theftRepository, EntityManagerInterface $entityManager)
    {

        $list = $theftRepository->findAllNoProcessAi();

       if(!$list) {
           return $this->json(["no data"],200);
       }


       $marchine = new MarchineLearningService();

       foreach ( $list as $theft) {

          $response = $marchine->test($theft->getDescription());

           if(isset($response["prediction"])) {

               $theft->setAiProcces(new \DateTime());
               $theft->setResponse($response["prediction"]);

               if($response["prediction"] == 'Furto Simples') {
                   $theft->setStatus("Negado");
               }else{
                   $theft->setStatus("Aprovado");
               }

               $entityManager->persist($theft);
               $entityManager->flush();

           }


       }


        return $this->json(["proccess"],200);
    }
}
