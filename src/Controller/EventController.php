<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Form\EventType;
use App\Form\EventEditType;
use App\Form\BannerType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @Route("/event")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/", name="event_index", methods={"GET"})
     */
    public function index(): Response
    {
        $user = $this->getUser();

        $eventOrganized = $user->getEventOrganized();
        $eventGoing = $user->getEventGoing();
        $events = array_merge($eventGoing->toArray(), $eventOrganized->toArray());

        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/new", name="event_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $event->setOrganizer($user);
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $event->setImage($imageFileName);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($event);
                $entityManager->flush();
            }
            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{event}/user/{user}", name="event_user_toggle", methods={"POST"})
     */
    public function invite(Event $event, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($event->hasParticipant($user)) {
            $event->removeParticipant($user);
        } else {
            $event->addParticipant($user);
        }
        $entityManager->flush();
        return $this->redirectToRoute('event_show', ['id' => $event->getId()]);
    }

    /**
     * @Route("/{id}", name="event_show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="event_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Event $event, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(EventEditType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($event);
                $entityManager->flush();
            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/{id}/editBanner", name="event_edit_banner", methods={"GET","POST"})
     */
    public function editBanner(Request $request, Event $event, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(BannerType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $event->setImage($imageFileName);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($event);
                $entityManager->flush();
            }
            return $this->redirectToRoute('event_edit', ['id' => $event->getId()]);
        }

        return $this->render('event/editBanner.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
            $fileToDelete = __DIR__ . '/../../public/uploads/' . $event->getImage();
            if (file_exists($fileToDelete)) {
                unlink($fileToDelete);
            }
        }

        return $this->redirectToRoute('event_index');
    }
}
