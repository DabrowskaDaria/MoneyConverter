<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterForm;
use App\Model\UserDTO;
use App\Repository\UserRepository;


use App\Service\EmailService;
use App\Token;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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

    /*#[Route('/login', name:'login')]
    public function login(): Response
    {
        return $this->render('User/login.html.twig');
    }*/

    #[Route('/remindPassword', name: 'remindPassword',methods: ['GET'])]
    public function remindPasswordForm() : Response
    {
        return $this->render('User/remindPassword.html.twig');
    }

    #[Route('/remindPassword',name: 'remindPassword_post', methods: ['POST'])]
    public function remindPassword(Request $request,UserRepository $userRepository, EmailService $emailService, Token $token,UrlGeneratorInterface $urlGenerator) : Response
    {
        $email=$request->request->get('email');
        $user=$userRepository->findOneByEmail($email);
        if(!$user){
            $this->addFlash('info','Nie znaleziono użytkownika z takim adresem e-mail.');
            return $this->redirectToRoute('remindPassword');
        }
        $token=$token->generateToken($user,$userRepository);
        $resetLink=$urlGenerator->generate('resetPassword',['token'=>$token],UrlGeneratorInterface::ABSOLUTE_URL);

        //$content=sprintf("<p> Witaj!!<br> Kliknij w link, aby zresetować hasło: </p><a href='%s' >Resetuj hasło</a>",$resetLink);

        $emailService->sendMail($user->getEmail(),'Resetowanie hasła',$user->getName(), $user->getSurname(),$resetLink);

        $this->addFlash('success', 'E-mail z instrukcjami resetowania hasła został wysłany.');
        return $this->redirectToRoute('remindPassword');
    }


    #[Route('/resetPassword', name:'resetPassword', methods: ['GET'])]
    public function resetPasswordForm() : Response
    {
        return $this->render('User/resetPassword.html.twig');
    }

    #[Route('/resetPassword', name: 'resetPassword_post', methods: ['POST'])]
    public function resetPassword(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher):Response
    {
        $token=$request->query->get('token');
        $user=$userRepository->findOneByToken($token);
        if(!$user)
        {
            $this->addFlash('error', 'Token jest niepoprawny');
            return $this->redirectToRoute('login');
        }

        if($user->getTokenExpiresAt()< new \DateTime()){
            $this->addFlash('error', 'Token wygasł');
            return $this->redirectToRoute('login');
        }

        $password=$request->request->get('password');
        $repeatPassword=$request->request->get('repeatPassword');

        if($password!==$repeatPassword){
            $this->addFlash('error', "Wprowadź identyczne hasła.");
            return $this->redirectToRoute('resetPassword');
        }


        $user->setPassword($passwordHasher->hashPassword($user,$password));
        $user->setToken('');
        $user->setTokenExpiresAt(null);
        $userRepository->save($user);
        $this->addFlash('success', "Hasło zostało zmienione.");
        return $this->redirectToRoute('resetPassword');
    }

    #[Route('/userActivity', name: 'userActivity')]
    #[IsGranted('ROLE_USER')]
    public function showUserActivity(): Response
    {
        return $this->render('User/userActivity.html.twig');
    }

    #[\Symfony\Component\Routing\Attribute\Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('User/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}