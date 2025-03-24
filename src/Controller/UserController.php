<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterForm;
use App\Form\ResetPasswordForm;
use App\Model\PasswordDTO;
use App\Model\UserDTO;
use App\Repository\UserRepository;
use App\Service\EmailService;
use App\Service\TokenGenerator;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class UserController extends AbstractController
{

    private const TEMPLATE_ACTIVATION_ACCOUNT = "email/emailActiveAccount.html.twig";
    private const TEMPLATE_RESET_PASSWORD = "email/emailResetPassword.html.twig";
    private string $path;


    public function __construct(
        private UserRepository $userRepository,
        private EmailService $emailService,
        private UrlGeneratorInterface $urlGenerator,
        private UserPasswordHasherInterface $passwordHasher,
        private TokenGenerator $tokenGenerator,
        private ParameterBagInterface $parameterBag
    ) {
        $this->path = $this->parameterBag->get('path');
    }

    #[Route('/{_locale}/register', name: 'registerUser')]
    public function register(Request $request): Response
    {
        $userDTO = new UserDTO();
        $form = $this->createForm(RegisterForm::class, $userDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User(
                $userDTO->getName(),
                $userDTO->getSurname(),
                $userDTO->getEmail(),
            );
            $hashedPassword = $this->passwordHasher->hashPassword($user, $userDTO->getPassword());
            $user->setPassword($hashedPassword);

            $token = $this->tokenGenerator->generateToken();
            $user->setTokenAndTokenExpiresAt($token, '+10 minutes');
            $this->userRepository->save($user);
            $activationLink = $this->urlGenerator->generate(
                'activationLink',
                ['token' => $token],
                UrlGeneratorInterface::ABSOLUTE_URL
            );


            $template = self::TEMPLATE_ACTIVATION_ACCOUNT;
            $this->emailService->sendMail([
                'to' => $user->getEmail(),
                'subject' => 'Aktywacja konta',
                'link' => $activationLink,
                'template' => $template,
                'name' => $user->getName(),
                'surname' => $user->getSurname(),
            ]);
            return $this->redirectToRoute('registerUser');
        }

        return $this->render('User/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{_locale}/activateAccount/{token}', name: 'activationLink')]
    public function activateAccount(string $token): Response
    {
        $user = $this->userRepository->findOneByToken($token);

        if ($user->hasValidToken($token)) {
            $this->userRepository->save($user);
            return new Response('Aktywowano konto');
        } else {
            $this->userRepository->save($user);
            return new Response('Token jest wygasł lub jest niepoprawny');
        }
    }

    #[Route('/{_locale}/remindPassword', name: 'remindPassword', methods: ['GET'])]
    public function remindPasswordForm(): Response
    {
        return $this->render('User/remindPassword.html.twig');
    }

    #[Route('/{_locale}/remindPassword', name: 'remindPassword_post', methods: ['POST'])]
    public function remindPassword(Request $request): Response
    {
        $email = $request->request->get('email');
        $user = $this->userRepository->findOneByEmail($email);
        if (!$user) {
            $this->addFlash('info', 'Nie znaleziono użytkownika z takim adresem e-mail.');
            return $this->redirectToRoute('remindPassword');
        }

        $token = $this->tokenGenerator->generateToken();
        $user->setTokenAndTokenExpiresAt($token, '+5 minutes');
        $this->userRepository->save($user);

        $resetLink = $this->urlGenerator->generate(
            'resetPassword',
            ['token' => $token],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $template = self::TEMPLATE_RESET_PASSWORD;
        $this->emailService->sendMail([
                'to' => $user->getEmail(),
                'subject' => 'Resetowanie hasła',
                'link' => $resetLink,
                'template' => $template,
                'name' => $user->getName(),
                'surname' => $user->getSurname()
            ]
        );

        $this->addFlash('success', 'E-mail z instrukcjami resetowania hasła został wysłany.');
        return $this->redirectToRoute('remindPassword');
    }

    #[Route(path: '/{_locale}/resetPassword/success', name: 'reset_password_success', methods: ['GET'])]
    public function showRestPasswordSuccess(): Response
    {
        return $this->render('User/restPasswordSuccess.html.twig');
    }

    #[Route('/{_locale}/resetPassword/{token}', name: 'resetPassword', methods: ['GET', 'POST'])]
    public function resetPassword(string $token, Request $request): Response
    {
        $user = $this->userRepository->findOneByToken($token);

        if (!$user) {
            return new Response('Token jest niepoprawny');
        }

        if (!$user->hasValidToken($token)) {
            $this->userRepository->save($user);
            return new Response('Token wygasł lub jest niepoprawny');
        }

        $passwordDTO = new PasswordDTO();
        $form = $this->createForm(ResetPasswordForm::class, $passwordDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passwordData = $form->getData();
            $password = $passwordData->getPassword();
            $repeatPassword = $passwordData->getRepeatPassword();

            if ($password !== $repeatPassword) {
                $this->addFlash('error', "Wprowadź identyczne hasła.");
                return $this->redirectToRoute('resetPassword', ['token' => $token]);
            }

            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $user->clearToken();
            $this->userRepository->save($user);
            $this->addFlash('success', "Hasło zostało zmienione.");

            return $this->redirectToRoute('reset_password_success');
        }

        return $this->render('User/resetPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{_locale}/userActivity', name: 'userActivity')]
    #[IsGranted('ROLE_USER')]
    public function showUserActivity(): Response
    {

        $data = [];
        if (file_exists($this->path)) {
            $file = fopen($this->path, "r");
            while (($row = fgetcsv($file, 1000, ',')) !== false) {
                $data[] = $row;
            }
            fclose($file);
        }
        return $this->render('User/userActivity.html.twig', [
            'data' => $data,
        ]);
    }

    #[Route(path: '/{_locale}/login', name: 'app_login')]
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

    #[Route(path: '/{_locale}/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }

    #[Route(path: '{_locale}/accountManager', name: 'accountManager')]
    public function showAccountManager(): Response
    {
        return $this->render('User/accountManager.html.twig');
    }

}