<?php

namespace App\Controller;

use App\Entity\Courrier;
use App\Entity\HistoriqueCourrier;
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
    public function index(EntityManagerInterface $em, Security $security): Response
    {
        $user = $security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour voir vos courriers.');
        }

        $query = $em->createQuery(
            'SELECT c
            FROM App\Entity\Courrier c
            WHERE :user MEMBER OF c.destinataire OR c.expediteur = :user'
        )->setParameter('user', $user);

        $courriers = $query->getResult();

        return $this->render('courrier/index.html.twig', [
            'courriers' => $courriers,
        ]);
    }

    #[Route('/courrier/new', name: 'courrier_new')]
    public function new(Request $request, EntityManagerInterface $em, Security $security): Response
    {
        // Vérifiez si l'utilisateur a le rôle ROLE_ADMIN
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à créer un courrier.');
        }

        $courrier = new Courrier();
        $courrier->setExpediteur($security->getUser());
        $courrier->setStatus('en_attente');//Definit le statut par defaut

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

            $this->addFlash('success', 'Courrier envoyé avec succès !');
            return $this->redirectToRoute('app_courrier');
        }

        return $this->render('courrier/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/courrier/supprimer/{id}', name: 'supprimer_courrier')]
    public function supprimerCourrier(int $id, CourrierRepository $courrierRepository, EntityManagerInterface $em): Response
    {
        // Vérifiez si l'utilisateur a le rôle ROLE_ADMIN
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer un courrier.');
        }

        $courrier = $courrierRepository->find($id);

        if (!$courrier) {
            throw $this->createNotFoundException('Courrier non trouvé');
        }

        $courrierHistorique = new HistoriqueCourrier();
        $courrierHistorique->setObjet($courrier->getObjet());
        $courrierHistorique->setExpediteur($courrier->getExpediteur()->getEmail());
        $courrierHistorique->setDestinataire(array_map(fn($user) => $user->getEmail(), $courrier->getDestinataire()->toArray())); // Collecte des emails des destinataires
        $courrierHistorique->setDateEnvoie($courrier->getDateEnvoi());
        $courrierHistorique->setSupprime(true);

        $courrierHistorique->addCourrier($courrier);

        $em->persist($courrierHistorique);
        $em->remove($courrier);
        $em->flush();

        $this->addFlash('success', 'Le courrier a été supprimé définitivement.');

        return $this->redirectToRoute('app_courrier');
    }

    #[Route('/courrier/modifier/{id}', name: 'courrier_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $em, CourrierRepository $courrierRepository): Response
    {
        // Vérifiez si l'utilisateur a le rôle ROLE_ADMIN
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier un courrier.');
        }

        $courrier = $courrierRepository->find($id);

        if (!$courrier) {
            throw $this->createNotFoundException('Courrier non trouvé');
        }

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
                    return $this->redirectToRoute('courrier_edit', ['id' => $id]);
                }
            }
            $em->flush();
            $this->addFlash('success', 'Courrier mis à jour avec succès !');
            return $this->redirectToRoute('app_courrier');
        }

        return $this->render('courrier/modifier.html.twig', [
            'form' => $form->createView(),
            'courrier' => $courrier, // Passer le courrier à la vue si besoin
        ]);
    }

    // Ouvrir courrier
    #[Route('/courrier/ouvrir/{id}', name: 'app_courrier_ouvrir')]
    public function ouvrirCourrier(int $id, EntityManagerInterface $em, Security $security): Response
    {
        $user = $security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour ouvrir un courrier.');
        }
        $courrier = $em->getRepository(Courrier::class)->find($id);

        if (!$courrier) {
            throw $this->createNotFoundException('Le courrier n\'existe pas.');
        }
        if (!$courrier->getDestinataire()->contains($user) && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à ouvrir ce courrier.');
        }

        if ($courrier->getDestinataire()->contains($user)) {
            $courrier->setDateReception(new \DateTime('now'));
            $courrier->setStatus('Lu');
        }

        $em->persist($courrier);
        $em->flush();

        return $this->render('courrier/ouvrir_courrier.html.twig', [
            'courrier' => $courrier,
            'message' => 'Le courrier a été ouvert avec succès.',
        ]);
    }

    #[Route('/courrier/recherche', name: 'app_courrier_recherche')]
    public function recherche(Request $request, CourrierRepository $courrierRepository): Response
    {
        $searchTerm = $request->query->get('searchTerm', '');
        $courriers = $courrierRepository->searchCourriers($searchTerm);

        return $this->render('courrier/index.html.twig', [
            'courriers' => $courriers,
        ]);
    }
}
