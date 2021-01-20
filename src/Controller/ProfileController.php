<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('user_show', ['id' => $this->getUser()->getId()]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}
