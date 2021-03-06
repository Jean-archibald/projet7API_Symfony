<?php

namespace App\Controller;

use App\Notification\ContactNotification;
use App\Repository\PartnersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{


    /**
     * @Route("/confirmed/{ConfirmationToken}", name="app_confirmed_mail")
     */
    public function confirmedMail(PartnersRepository $partnersRepository, string $ConfirmationToken, EntityManagerInterface $manager,Request $request,UserPasswordEncoderInterface $encoder)
    {
        $partners = $partnersRepository->findOneBy(['confirmationToken' => $ConfirmationToken]);
        $partnersName = $partners->getUsername();
        $partnersStatus = $partners->getStatusConfirmed();
        $partnersRepository->confirmedMailReception($manager,$request,$encoder,$partners);

        if (isset($_POST) && !empty($_POST) && $partnersStatus == true)
        {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/passwordEdit.html.twig', ['username' => $partnersName]);
    }


    /**
     * @Route("/forgotPassword", name="app_forgotten_password")
     */
    public function forgotPassword(Request $request, PartnersRepository $partnersRepository,MailerInterface $mailer,ContactNotification $notification)
    {
        if ($request->isMethod('POST')) {
            $partners = $partnersRepository->findOneBy(['email' => ($_POST['email'])]);
            if (isset($partners)) {
                $partnersRepository->sendMailConfirmation($partners, $notification, $mailer);
                return $this->redirectToRoute('home');
            }
        }

        return $this->render('security/passwordForgot.html.twig');
    }

    /**
     * @Route("/login_check", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }


    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/confirmation", name="confirmationLogin")
     */
    public function confirmationLogin()
    {
        $partners = $this->getUser();
        $partnersStatus = $partners->getStatusConfirmed();

        if ($partnersStatus === true)
        {
            return $this->redirectToRoute('easyadmin');
        }
        else
        {
            return $this->redirectToRoute('app_login');
        }

    }

    /**
     * @Route("/front", name="easyadmin")
     */
    public function front()
    {

    }

}