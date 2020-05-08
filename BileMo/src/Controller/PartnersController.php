<?php

namespace App\Controller;

use App\Entity\Partners;
use App\Repository\PartnersRepository;
use Symfony\Component\Mailer\MailerInterface;
use App\Notification\ContactNotification;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;


class PartnersController extends EasyAdminController
{
    protected $em;

    public function __construct( SerializerInterface $serializer,ValidatorInterface $validator, EntityManagerInterface $em, PartnersRepository $partnersRepository, MailerInterface $mailer,ContactNotification $notification, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->notification = $notification;
        $this->partnersRepository = $partnersRepository;
        $this->encoder = $encoder;
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    protected function createNewEntity()
    {
        $data = new Partners();
        return new $data();
    }

    protected function persistEntity($data)
    {
        $this->partnersRepository->persistPartners($data, $this->encoder, $this->em);
        $this->partnersRepository->sendMailConfirmation($data, $this->notification, $this->mailer);
    }

    protected function removeEntity($data)
    {
        $this->em->remove($data);
        $this->em->flush();
    }

    protected function updateEntity($data)
    {
        $this->partnersRepository->updatePartners($data, $this->em);

    }



}