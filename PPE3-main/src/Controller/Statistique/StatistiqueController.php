<?php

namespace App\Controller\Statistique;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Security;

use App\Service\Statistique;

use App\Repository\CompteRenduRepository;


#[Route('/statistique')]
class StatistiqueController extends AbstractController
{
    private CompteRenduRepository $compteRenduRepository;

    public function __construct(
        Security $security,
        Statistique $statistique,
        CompteRenduRepository $compteRenduRepository)
    {
        $this->security = $security;
        $this->statistique = $statistique;
        $this->compteRenduRepository = $compteRenduRepository;
    }


    #[Route('/index', name: 'statistique.index')]
    public function index(): Response
    {
        $listeCompteRendu = $this->compteRenduRepository->findAllWithUser($this->security->getUser());
        
        $medicamentsPresentesParMois = $this->statistique->determineMedicamentsPresentesParMois($listeCompteRendu);
        $medicamentsOffertsParMois = $this->statistique->determineMedicamentsOffertsParMois($listeCompteRendu);
        $listeCoutsParMois = $this->statistique->determineCoutMedicamentsOffertsParMois($listeCompteRendu);

        return $this->render('statistique/index.html.twig', [
            'medicamentsPresentesParMois' => $medicamentsPresentesParMois,
            'medicamentsOffertsParMois' => $medicamentsOffertsParMois,
            'listeCoutsParMois' => $listeCoutsParMois
        ]);
    }
}
