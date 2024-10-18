<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{

  private $entityManager;

  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->entityManager = $entityManager;

  }
  #[Route('/inscription', name: 'registration')]
  public function index(
    Request $request,
    UserPasswordHasherInterface $hasher,
  ): Response {
    $user = new User();
    $form = $this->createForm(RegisterType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $password = $hasher->hashPassword($user,$user->getPassword());
      $user ->setPassword($password);
     
      $form = $form->getData();

      $this->entityManager->persist($user);
      $this->entityManager->flush();

      return $this->redirectToRoute('registration');

    }
    return $this->render('registration/index.html.twig', [
      'form' => $form->createView()
    ]);
  }
}
