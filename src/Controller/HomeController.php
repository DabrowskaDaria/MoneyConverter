<?php

declare(strict_types=1);

namespace App\Controller;


use App\Repository\CurrencyRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    public function __construct(private CurrencyRepository $currencyRepository)
    {
    }

    #[Route('/{_locale}', name: 'app_home')]
    public function index(): Response
    {
        $currencies = $this->currencyRepository->findAll();

        return $this->render('home/index.html.twig', [
            'currencies' => $currencies,
            'date' =>$currencies[0]->getCreatedAt()->format('d-m-Y'),
        ]);
    }

    #[Route('/', name: 'home')]
    public function showHome(): Response
    {
       return $this->redirectToRoute('app_home', [
           '_locale' => 'pl',
       ]);
    }

    #[Route('/{_locale}/homePageForUser', name: 'homePageForUser')]
    #[IsGranted('ROLE_USER')]
    public function homePageForUser(): Response
    {
        $curencies = $this->currencyRepository->findAll();
        return $this->render('home/homePageForUsers.html.twig', [
            'currencies' => $curencies,
            'date' =>$curencies[0]->getCreatedAt()->format('d-m-Y'),
        ]);
    }

    #[Route('/{_locale}/changeLanguage', name: 'change_language')]
    public function changeLanguage(Request $request,  SessionInterface $session): Response
    {
        $language=$request->get('language');
        $session->set('_locale', $language);
        return $this->redirectToRoute('app_home',['_locale'=>$language]);
    }
}
