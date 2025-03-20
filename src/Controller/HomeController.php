<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Currencies;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $currencies = $entityManager->getRepository(Currencies::class)->findAll();

        return $this->render('home/index.html.twig', [
            'currencies' => $currencies,
            'date' =>$currencies[0]->getCreatedAt()->format('d-m-Y'),
        ]);
    }

    #[Route('/homePageForUser', name: 'homePageForUser')]
    #[IsGranted('ROLE_USER')]
    public function homePageForUser(EntityManagerInterface $entityManager): Response
    {
        $curencies = $entityManager->getRepository(Currencies::class)->findAll();
        return $this->render('home/homePageForUsers.html.twig', [
            'currencies' => $curencies,
            'date' =>$curencies[0]->getCreatedAt()->format('d-m-Y'),
        ]);
    }


}
