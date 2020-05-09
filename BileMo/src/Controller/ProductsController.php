<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class ProductsController extends EasyAdminController
{
    protected $em;

    public function __construct(EntityManagerInterface $em,ProductsRepository $productsRepository)
    {
        $this->em = $em;
        $this->productsRepository = $productsRepository;
    }

    protected function createNewEntity()
    {
        $data = new Products();

        return new $data();
    }


    protected function persistEntity($data)
    {
        $this->productsRepository->persistProducts($data,$this->em);
    }

    protected function removeEntity($data)
    {
        $this->em->remove($data);
        $this->em->flush();
    }

    protected function updateEntity($data)
    {
        $this->productsRepository->persistProducts($data,$this->em);
    }
}