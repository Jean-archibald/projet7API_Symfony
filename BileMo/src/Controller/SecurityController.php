<?php

namespace App\Controller;

use App\Repository\PartnersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

}