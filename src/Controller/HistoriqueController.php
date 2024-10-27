<?php

namespace App\Controller;

use App\Entity\HistoriqueCourrier;
use App\Repository\HistoriqueCourrierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoriqueController extends AbstractController
{
    private HistoriqueCourrierRepository $historiqueCourrierRepository;

    

    public function __construct(HistoriqueCourrierRepository $historiqueCourrierRepository)
    {
        $this->historiqueCourrierRepository = $historiqueCourrierRepository;
    }


    #[Route('/historique', name: 'app_historique')]
    public function index(HistoriqueCourrierRepository $h): Response
    {
        $historique = $h->findAll();
        return $this->render('historique/index.html.twig', [
            'historique' =>  $historique
        ]);
    }

    #[Route('/historique/supprimer/{id}', name: 'supprimer_historique')]
    public function supprimerHistorique(int $id, HistoriqueCourrierRepository $historiqueCourrierRepository): Response
    {

        $historiqueCourrier = $historiqueCourrierRepository->find($id);

 
        if (!$historiqueCourrier) {
            throw $this->createNotFoundException('Historique de courrier non trouvé.');
        }


        $historiqueCourrierRepository->remove($historiqueCourrier, true); // Flush après la suppression

     
        return $this->redirectToRoute('app_historique'); // Assurez-vous que la route existe
    }
}
