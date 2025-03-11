<?php

namespace App\Controller;

use App\Entity\Currencies;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $currencies=$entityManager->getRepository(Currencies::class)->findAll();

        return $this->render('home/index.html.twig',[
            'currencies'=>$currencies,
        ]);
    }

    #[Route('/homePageForUser', name: 'homePageForUser')]
    public function homePageForUser(EntityManagerInterface $entityManager): Response
    {
        $curencies=$entityManager->getRepository(Currencies::class)->findAll();
        return $this->render('home/homePageForUsers.html.twig',[
            'currencies'=>$curencies,
        ]);
    }

}
