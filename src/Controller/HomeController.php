<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function doormat(): Response
    {
        return $this->render('home/doormat.html.twig');
    }

    /**
     * @Route("/login", name="home_login")
     */
    public function login(): Response
    {
        return $this->render('home/login.html.twig');
    }

    /**
     * @Route("/index", name="home_index")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
}
