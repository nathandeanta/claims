<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class EmailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail($to, $body): void
    {
        $email = (new Email())
            ->from(new Address($_ENV["MAIL_FROM"],'Troca de senha Guardian Phone' ))
            ->to($to)
            ->subject('Troca de senha')
            ->html($body);

        $this->mailer->send($email);
    }

    public function sendMailPassword($email,$name, ?string $getCode)
    {

        $url = $ENV["URL"]?? 'url-test';

        $url.="/updatePassword/".$getCode;

        $html = '<!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Redefinição de Senha</title>
                </head>
                <body style="font-family: Arial, sans-serif;">
                
                    <div style="max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px;">
                
                        <h2 style="color: #333;">Redefinição de Senha</h2>
                
                        <p>Olá '.$name.',</p>
                
                        <p>Você solicitou a redefinição de senha para a sua conta no guardian phone. Para continuar o processo de redefinição, clique no link abaixo:</p>
                
                        <p><a href="'.$url.'" style="background-color: #4CAF50; color: white; padding: 10px 15px; text-decoration: none; display: inline-block; border-radius: 5px;">Redefinir Senha</a></p>
                
                        <p>Se você não solicitou a redefinição de senha, ignore este e-mail. O link de redefinição de senha é válido por 20 minutos.</p>
                
                        <p>Obrigado,<br>
                        Guardian<br>
                        
                
                    </div>
                
                </body>
                </html>
';


        $this->sendMail($email, $html);
    }

}