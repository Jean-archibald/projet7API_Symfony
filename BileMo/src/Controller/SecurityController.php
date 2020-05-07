<?php

namespace App\Controller;

use App\Repository\PartnersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpClient\HttpClient;

class SecurityController extends AbstractController
{


    /**
     * @Route("/confirmed/{ConfirmationToken}", name="app_confirmed_mail")
     */
    public function confirmedMail(PartnersRepository $partnersRepository, string $ConfirmationToken, EntityManagerInterface $manager)
    {
        $partners = $partnersRepository->findOneBy(['confirmationToken' => $ConfirmationToken]);
        if (isset($partners)) {
            $partnersRepository->confirmedMailPartners($partners, $manager);
            return $this->redirectToRoute('login_check');
        } else {
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/forgotPassword", name="app_forgotten_password")
     */
    public function forgotPassword()
    {

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
     * @Route("/front", name="easyadmin")
     */
    public function front()
    {

    }

}