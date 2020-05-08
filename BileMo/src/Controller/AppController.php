<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;




class AppController extends Controller
{
    /**
     * @Route("/", name="")
     */
    public function index()
    {
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/home", name="home")
     */
    public function home()
    {
        return $this->render('api/home.html.twig');
    }

    /**
     * @Route("/docs", name="documentation")
     */
    public function documentation()
    {
        return $this->render('api/doc.html.twig');
    }



}