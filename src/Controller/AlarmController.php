<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlarmController extends AbstractController
{
    /**
     * @Route("/alarm", name="alarm")
     */
    public function index(): Response
    {
        return $this->render('alarm/index.html.twig', [
            'controller_name' => 'AlarmController',
        ]);
    }

    /**
     * @Route("/confirm", name="confirm_sos")
     */
    public function confirm(): Response
    {
        return $this->render('alarm/confirmationSos.html.twig', [
            'controller_name' => 'AlarmController',
        ]);
    }
}
