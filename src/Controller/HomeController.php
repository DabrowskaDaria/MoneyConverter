<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Currencies;
use App\Repository\CurrencyRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    public function __construct(private CurrencyRepository $currencyRepository)
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $currencies = $this->currencyRepository->findAll();

        return $this->render('home/index.html.twig', [
            'currencies' => $currencies,
            'date' =>$currencies[0]->getCreatedAt()->format('d-m-Y'),
        ]);
    }

    #[Route('/homePageForUser', name: 'homePageForUser')]
    #[IsGranted('ROLE_USER')]
    public function homePageForUser(): Response
    {
        $curencies = $this->currencyRepository->findAll();
        return $this->render('home/homePageForUsers.html.twig', [
            'currencies' => $curencies,
            'date' =>$curencies[0]->getCreatedAt()->format('d-m-Y'),
        ]);
    }


}
