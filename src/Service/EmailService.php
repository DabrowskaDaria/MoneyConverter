<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(
        string $to,
        string $subject,
        string $name,
        string $surname,
        string $link,
        string $template
    ): void {
        /*$resetLink= sprintf(
            'http://127.0.0.1:8000/remindPassword/%s',
            $token);
          */
        $email = (new TemplatedEmail())
            ->from('daria.dabrowska@yellows.eu')
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context([
                'name' => $name,
                'surname' => $surname,
                'link' => $link
            ]);
        $this->mailer->send($email);
    }

}