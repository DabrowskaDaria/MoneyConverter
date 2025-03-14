<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterForm;
use App\Model\UserDTO;
use App\Repository\UserRepository;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/register', name: 'registerUser')]
    public function register(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher) : Response
    {
        $userDTO= new UserDTO();
        $form=$this->createForm(RegisterForm::class, $userDTO);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user= new User(
                $userDTO->getName(),
                $userDTO ->getSurname(),
                $userDTO->getEmail(),
                "",
                "",
                false
            );
            $hashedPassword=$passwordHasher->hashPassword($user, $userDTO->getPassword());
            $user->setPassword($hashedPassword);
            $userRepository->save($user);
            return $this->redirectToRoute('registerUser');
        }

        return $this->render('User/register.html.twig',[
            'form'=>$form->createView(),
        ]);
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