<?php

namespace App\Controller;

use App\Entity\Currencies;
use App\Service\EmailService;
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
        $currencies=$entityManager->getRepository(Currencies::class)->findAll();

        return $this->render('home/index.html.twig',[
            'currencies'=>$currencies,
        ]);
    }

    #[Route('/homePageForUser', name: 'homePageForUser')]
    #[IsGranted('ROLE_USER')]
    public function homePageForUser(EntityManagerInterface $entityManager): Response
    {
        $curencies=$entityManager->getRepository(Currencies::class)->findAll();
        return $this->render('home/homePageForUsers.html.twig',[
            'currencies'=>$curencies,
        ]);
    }

    #[Route('/test-email', name: 'test_email')]
    public function testEmail(EmailService $emailService): Response
    {
        $emailService->sendMail(
            'daria.dabrowska@yellows.eu',
            'Testowy e-mail',
            '<p>To jest testowy e-mail wysłany z Symfony.</p>'
        );

        return new Response('E-mail został wysłany!');
    }


}
