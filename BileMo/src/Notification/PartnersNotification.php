<?php 
namespace App\Notification;

use App\Entity\Partners;
use Twig\Environment;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ContactNotification {

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $renderer;

    public function __construct(MailerInterface $mailer, Environment $renderer,UrlGeneratorInterface $router)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
        $this->router = $router;
    
    }

    public function notify(Partners $partners)
    {
        $urlConfirmedToken = $this->router->generate('app_confirmed_mail', [
            'Apitoken' => $partners->getApitoken()
        ]); 

        $email = (new Email())
                    ->from('jvjlondon@outlook.com')
                    ->to($partners->getEmail())
                    ->subject('Activate your account, Registration of ' . $partners->getUsername())
                    ->replyTo('jvjlondon@outlook.com')
                    ->html($this->renderer->render('emails/confirmation.html.twig',[
                        'user' => $partners,
                        'urlConfirmedToken' => 'http://localhost:8000' . $urlConfirmedToken 
                    ]));

        /** @var Symfony\Component\Mailer\SentMessage $sentEmail */
        $this->mailer->send($email);
    }

}