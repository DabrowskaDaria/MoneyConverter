<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class EmailService
{
    private MailerInterface $mailer;
    private string $addresEmail;

    public function __construct(MailerInterface $mailer, ParameterBagInterface $parameterBag)
    {
        $this->mailer = $mailer;
        $this->addresEmail= $parameterBag->get("addresEmail");
    }

    public function sendMail(
        array $params
    ): void {

        $email = (new TemplatedEmail())
            ->from($this->addresEmail)
            ->to($params['to'])
            ->subject($params['subject'])
            ->htmlTemplate($params['template'])
            ->context([
                'name' => $params['name'],
                'surname' => $params['surname'],
                'link' => $params['link']
            ]);
        $this->mailer->send($email);
    }

}