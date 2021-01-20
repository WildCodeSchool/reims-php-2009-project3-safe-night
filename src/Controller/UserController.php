<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer as NormalizerAbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatarFile = $form->get('avatar')->getData();
            if ($avatarFile) {
                $avatarFileName = $fileUploader->upload($avatarFile);
                $user->setAvatar($avatarFileName);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }
            return $this->redirectToRoute('home_login');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, FileUploader $fileUploader): Response
    {
        //$user->setAvatar(new File($this->getParameter('image_directory') . '/' . $user->getAvatar()));
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatarFile = $form->get('avatar')->getData();
            if ($avatarFile) {
                $avatarFileName = $fileUploader->upload($avatarFile);
                $user->setAvatar($avatarFileName);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }
            $this->getDoctrine()->getManager()->flush();
            $id = $user->getId();
            return $this->redirectToRoute('user_show', ['id' => $id]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            $fileToDelete = __DIR__ . '/../../public/uploads/' . $user->getAvatar();
            if (file_exists($fileToDelete)) {
                unlink($fileToDelete);
            }
        }
        $session = new Session();
        $session->invalidate();

        return $this->redirectToRoute('app_logout');
    }

    /**
     * @Route("/{id}/friend", methods={"GET"}, requirements={"id"="\d+"},name="friend_show")
     */
    public function showFriends(User $user): Response
    {
        return $this->render('user/myFriends.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{user}/friend", name="friend_add", methods={"POST"})
     */
    public function addFriend(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $userConnected = $this->getUser();
        $userConnected->addFriend($user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($userConnected);
        $entityManager->flush();
        $id = $userConnected->getId();
        return $this->redirectToRoute("user_friend_show", [
            'id' => $id
        ]);
    }

    /**
     * @Route("/{user}/friend", name="friend_remove", methods={"DELETE"})
     */
    public function removeFriend(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $userConnected = $this->getUser();
        $userConnected->removeFriend($user);

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userConnected);
            $entityManager->flush();
            $id = $userConnected->getId();
            return $this->redirectToRoute("user_friend_show", [
            'id' => $id
            ]);
        }
    }

    /**
     * @Route("/{id}/search", name="search", methods={"GET"})
     * @return Response
     */
    public function search(Request $request, UserRepository $userRepository): Response
    {
        $query = $request->query->get('q');
        if (null !== $query) {
            $users = $userRepository->findByQuery($query);
        }

        return $this->render('user/friendSearch.html.twig', [
            'users' => $users ?? [],
        ]);
    }

    /**
     * @Route("/{id}/autocomplete", name="autocomplete", methods={"GET"})
     * @return Response
     */
    public function autocomplete(Request $request, UserRepository $userRepository): Response
    {
        $query = $request->query->get('q');
        $encoder = new JsonEncoder();
        if (null !== $query) {
            $users = $userRepository->findByQuery($query);
        }
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);
        $response = new Response($serializer->serialize($users, 'json'));

        return $response;
    }
}
