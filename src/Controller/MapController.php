<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    /**
     * @Route("/map", name="map")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $events = [];
        $events = array_merge($user->getEventOrganized(), $user->getEventGoing());

        return $this->render('map/index.html.twig', [
            'controller_name' => 'MapController',
            'events' => $events
        ]);
    }
}
