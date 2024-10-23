<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegisterType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/utilisateur', name: 'app_user')]
    public function index(EntityManagerInterface $em): Response
    {    
        $users = $em->getRepository(User::class)->findAll();
   
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/utilisateur/modifier/{id}', name: 'user_edit')]
    public function edit(Request $request, User $user, UserPasswordHasherInterface $hasher, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getPassword()) {
                $password = $hasher->hashPassword($user, $user->getPassword());
                $user->setPassword($password);
            }

            $em->flush();

            return $this->redirectToRoute('app_user'); 
        }

        return $this->render('user/modifier.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/utilisateur/supprimer{id}', name: 'user_delete')]
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_user'); 
    }
}
