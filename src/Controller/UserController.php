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
use App\Entity\Friend;
use App\Repository\FriendRepository;
/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
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
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, FileUploader $fileUploader): Response
    {
        $user->setAvatar(new File($this->getParameter('image_directory') . '/' . $user->getAvatar()));
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
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
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

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/{id}/friends", methods={"GET"}, requirements={"id"="\d+"},name="friends_show")
     */
    public function showFriends(User $user, FriendRepository $friendRepository) : Response
    {
       
        if (!$user) {
            throw $this->createNotFoundException(
                'No user with id : '. $user->getId() .' found in user\'s table.'
            );
        }
        
        $friends = $friendRepository->findBy([
            'user' => $user
        ]);

        return $this->render('user/myFriends.html.twig', [
        'user' => $user, 'friends' => $friends
    ]);
    }

        /**
     * @Route("/{user}/friend/{friend}", name="friend_delete", methods={"DELETE"})
     */
    public function deleteFriend(Request $request, User $user, Friend $friend): Response
    {
        if ($this->isCsrfTokenValid('delete'.$friend->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($friend);
            $entityManager->flush();
        }

        return $this->redirectToRoute('friend_index');
    }

    /**
     * @Route("/search", name="search", methods={"GET"})
     * @return Response
     */
    public function search(Request $request, UserRepository $userRepository): Response
    {
        $query = $request->query->get('q');

        if (null !== $query) {
            $users = $userRepository->findByQuery($query);
        }

        return $this->render('user/index.html.twig', [
            'users' => $users ?? [],
        ]);
    }

    /**
     * @Route("/autocomplete", name="autocomplete", methods={"GET"})
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
