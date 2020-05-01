<?php

namespace App\Controller;

use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class ProductsController extends EasyAdminController
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    protected function createNewEntity()
    {
        $data = new Products();

        return new $data();
    }

    protected function persistEntity($data)
    {
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