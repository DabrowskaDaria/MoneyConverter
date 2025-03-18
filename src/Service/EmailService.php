<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(string $to,string $subject, string $name, string $surname, string $link): void
    {
      /*$resetLink= sprintf(
          'http://127.0.0.1:8000/remindPassword/%s',
          $token);
        */
      $email=(new TemplatedEmail())
          ->from('daria.dabrowska@yellows.eu')
          ->to($to)
          ->subject($subject)
          ->htmlTemplate('email/emailResetPassword.html.twig')
          ->context(['name' => $name,
              'surname' => $surname,
              'link' => $link]);
      $this->mailer->send($email);
    }

}