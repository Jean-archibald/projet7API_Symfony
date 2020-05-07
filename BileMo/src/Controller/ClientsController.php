<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Repository\ClientsRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;




class ClientsController extends EasyAdminController
{
    protected $em;

    public function __construct(EntityManagerInterface $em,ClientsRepository $clientsRepository)
    {
        $this->em = $em;
        $this->clientsRepository = $clientsRepository;
    }

    protected function createNewEntity()
    {
        $data = new Clients();

        return new $data();
    }

    protected function persistEntity($data)
    {
        $partners = $this->getUser();
        $partnersId = $partners->getId();
        $data->setPartnersId($partners, $partnersId );
        $data->setCreatedAt(new \Datetime());
        $this->em->persist($data);
        $this->em->flush();


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