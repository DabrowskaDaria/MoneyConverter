<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/register', name: 'registerUser')]
    public function register(UserRepository $userRepository) : Response
    {
        //$user = new User('Jan','Kowalski','kowalskixsz@gmail.com',password_hash('kowalski', PASSWORD_DEFAULT),'ewwewe',false);
        //$userRepository->save($user);
        return $this->render('User/register.html.twig');
    }

    #[Route('/login', name:'login')]
    public function login(): Response
    {
        return $this->render('User/login.html.twig');
    }

    #[Route('/remindPassword',name: 'remindPassword')]
    public function remindPassword() : Response
    {
        return $this->render('User/remindPassword.html.twig');
    }

    #[Route('/resetPassword', name: 'resetPassword')]
    public function resetPassword():Response
    {
        return $this->render('User/resetPassword.html.twig');
    }

    #[Route('/userActivity', name: 'userActivity')]
    public function showUserActivity(): Response
    {
        return $this->render('User/userActivity.html.twig');
    }
}