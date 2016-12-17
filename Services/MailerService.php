<?php

namespace AppBundle\Services;



use Symfony\Component\DependencyInjection\Container;

class MailerService
{

    private $conteiner;
    private $swift;

    public function __construct(\Swift_Mailer $swift_Mailer, Container $container)
    {
        $this->conteiner=$container;
        $this->swift=$swift_Mailer;
    }

    public function forgotPasswordSend($password, $subject, $emailtosend)
       {
           if($subject=="newpassword")
           {
               $subject="Нова парола";
           }

     $message = \Swift_Message::newInstance()
        ->setSubject($subject)
        ->setFrom('ivanovkbg@abv.bg')
        ->setTo($emailtosend)
        ->setBody(
            "Вашата нова парола за достъп е: ".$password." Съветваме Ви след като влезете във вашият профил да я промените.",
            'text/html'
        )
        ;
           $this->conteiner->get('mailer')->send($message);


           }

    public function welcomeMessage($subject, $emailtosend, $username)
    {
        if($subject=="newuser")
        {
            $subject="Добре дошъл в SpaceWar";
        }
         $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('ivanovkbg@abv.bg')
            ->setTo($emailtosend)
            ->setBody(
                "Здравей, ".$username.". Благодарим Ви, че се регистрирахте в SpaceWar. Пожелаваме Ви приятна игра.",
                'text/html'
            )
            ;
        $this->conteiner->get('mailer')->send($message);
    }


}
