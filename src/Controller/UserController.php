<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/register', name: ' registerUser')]
    public function register(UserRepository $userRepository) : Response
    {
        $user = new User();
        $user->setName('Jan');
        $user->setSurname('Kowalski');
        $user->setEmail('kowalskixz@gmail.com');
        $user->setPassword(password_hash('kowalski', PASSWORD_DEFAULT));
        $user->setToken('ewewe');
        $user->setActive(false);
        $userRepository->save($user);
        return new Response('Użytkownik został zapisany');
    }
}