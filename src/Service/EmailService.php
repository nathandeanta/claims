<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class EmailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail()
    {
        $email = (new Email())
            ->from(new Address('ncosta@deantaglobal.com', 'Nathan'))
            ->subject('Assunto do E-mail')
            ->text('Conteúdo do e-mail em formato de texto.');

        $this->mailer->send($email);
    }

}