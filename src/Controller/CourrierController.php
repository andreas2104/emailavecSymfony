<?php

namespace App\Controller;

use App\Entity\Courrier;
use App\Form\CourrierType;
use App\Repository\CourrierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CourrierController extends AbstractController
{
  #[Route('/courrier', name: 'app_courrier')]

  public function index(EntityManagerInterface $em): Response
  {
    $courriers = $em->getRepository(Courrier::class)->findAll();

    // $cours = $form->getData();
    return $this->render('courrier/index.html.twig', [
      'courriers' => $courriers,
    ]);
  }

  #[Route('/courrier/new', name: 'courrier_new')]
  public function new(Request $request, EntityManagerInterface $em): Response
  {
    $courrier = new Courrier();
    $form = $this->createForm(CourrierType::class, $courrier);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      /** @var UploadedFile $pieceJointeFile */
      $pieceJointeFile = $form->get('piece_jointe')->getData();
      if ($pieceJointeFile) {
        try {
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/courriers';
            $newFilename = uniqid() . '.' . $pieceJointeFile->guessExtension();
            $pieceJointeFile->move($destination, $newFilename);
            $courrier->setPieceJointe($newFilename);
        } catch (FileException $e) {
            $this->addFlash('error', 'Erreur lors de l\'envoi de la pièce jointe.');
            return $this->redirectToRoute('courrier_new');
        }
    }
      $em->persist($courrier);
      $em->flush();
      return $this->redirectToRoute('app_courrier');
    }
    return $this->render('courrier/new.html.twig', [
      'form' => $form->createView(),
    ]);
  }
  public function uploadPieceJointe($file)
  {
      // Si un fichier est téléchargé
      if ($file) {
          $filename = uniqid().'.'.$file->getClientOriginalExtension();
          $file->move($this->getParameter('upload_directory'), $filename);
          return $filename;
      }
      return null;
  }
  
  #[Route('/mescourriers', name:'app_mes_courriers')]
  public function mesCourriers(CourrierRepository $courrierRepository, Security $security)
  {
    $user = $security->getUser();

    $courriersEnvoyes = $courrierRepository->findCourriersEnvoyes($user);

    $courrieRecus = $courrierRepository->findCourriersRecus($user);

    return $this->render('courrier/mes_courriers.html.twig', [
      'courriersEnvoyes' => $courriersEnvoyes,
      'courrierRecus' => $courrieRecus,
    ]);
  }
}
