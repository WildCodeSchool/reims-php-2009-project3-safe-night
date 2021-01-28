<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventRepository;

class MapController extends AbstractController
{
    /**
     * @Route("/map", name="map")
     */
    public function index(EventRepository $eventRepository): Response
    {
        $user = $this->getUser();
        
        return $this->render('map/index.html.twig', [
            'controller_name' => 'MapController',
            'events' => $eventRepository->findAllEventRelativeToUser($user),
        ]);
    }
}
