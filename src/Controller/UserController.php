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

class UserController extends AbstractController
{

    /**
     * @Route("/user/new", name="user_new", methods={"GET","POST"})
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
     * @Route("/user/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/{id}/edit", name="user_edit", methods={"GET","POST"})
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
     * @Route("/user/{id}", name="user_delete", methods={"DELETE"})
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

        return $this->redirectToRoute('/');
    }

    /**
     * @Route("/user/{id}/friend", methods={"GET"}, requirements={"id"="\d+"},name="friend_show")
     */
    public function showFriends(User $user): Response
    {
        if (!$user) {
            throw $this->createNotFoundException(
                'No user with id : ' . $user->getId() . ' found in user\'s table.'
            );
        }

        return $this->render('user/myFriends.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/{userId}/friend", name="user_friend_add", methods={"POST"})
     */
    public function addFriend(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            //$user->addFriend($friend);
            $entityManager->flush();

            return $this->redirectToRoute('friend_show');
        }

        return $this->render('user/newFriend.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/user/{userId}/friend/{friendId}", name="user_friend_remove", methods={"DELETE"})
     */
    public function removeFriend(Request $request, User $user, User $friend, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $friend->getId(), $request->request->get('_token'))) {
            $user->removeFriend($friend);
            $entityManager->flush();
        }

        return $this->redirectToRoute('friend_index');
    }

    /**
     * @Route("/user/{id}/search", name="user_search", methods={"GET"})
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
     * @Route("/user/autocomplete", name="user_autocomplete", methods={"GET"})
     * @return Response
     */
    public function autocomplete(Request $request, UserRepository $userRepository): Response
    {
        $query = $request->query->get('q');

        if (null !== $query) {
            $users = $userRepository->findByQuery($query);
        }

        return $this->json($users ?? [], 200);
    }
}
