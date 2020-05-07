<?php

namespace App\Controller;

use App\Entity\Partners;
use App\Repository\PartnersRepository;
use Symfony\Component\Mailer\MailerInterface;
use App\Notification\ContactNotification;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class PartnersController extends EasyAdminController
{
    protected $em;

    public function __construct(EntityManagerInterface $em, PartnersRepository $partnersRepository, MailerInterface $mailer,ContactNotification $notification, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->notification = $notification;
        $this->partnersRepository = $partnersRepository;
        $this->encoder = $encoder;
    }

    protected function createNewEntity()
    {
        $data = new Partners();

        return new $data();
    }

    protected function persistEntity($data)
    {

        $data->setCreatedAt(new \Datetime());
        $data->setPassword('undefined');
        $data->setStatusConfirmed(0);
        $data->setRoles(["ROLE_USER"]);
        $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/','-_'), '=');
        $data->setConfirmationToken($token);
        $hash = $this->encoder->encodePassword($data, $data->getPassword());
        $data->setPassword($hash);
        $this->em->persist($data);
        $this->em->flush();
        $this->partnersRepository->sendMailConfirmation($data,$this->notification,$this->mailer);

    }

    protected function removeEntity($data)
    {
        $this->em->remove($data);
        $this->em->flush();
    }

    protected function updateEntity($data)
    {
        $data->setCreatedAt(new \Datetime());
        $this->em->persist($data);
        $this->em->flush();
    }



}