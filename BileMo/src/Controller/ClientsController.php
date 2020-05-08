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
        $this->clientsRepository->persistClients($data,$this->em,$partners,$partnersId);
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

    protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        $roles = $this->getUser()->getRoles();
        if ($roles[0] == "ROLE_ADMIN") {
            return $this->get('easyadmin.query_builder')->createListQueryBuilder($this->entity, $sortField, $sortDirection, $dqlFilter);
        }
        elseif (null === $dqlFilter) {
            $dqlFilter = sprintf('entity.partnersId = %s', $this->getUser()->getId());
        } else {
            $dqlFilter .= sprintf(' AND entity.partnersId = %s', $this->getUser()->getId());
        }

        return parent::createListQueryBuilder($entityClass, $sortDirection, $sortField, $dqlFilter);
    }



}