<?php

namespace App\Controller\CompteRendu;

use App\Entity\CompteRendu;
use App\Entity\Presentation;
use App\Form\CompteRenduType;
use App\Form\PresentationType;
use App\Repository\CompteRenduRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Security;


#[Route('/compteRendu')]
class CompteRenduController extends AbstractController
{
    private EntityManagerInterface $entityManagerInterface;
    private CompteRenduRepository $compteRenduRepository;
    private Security $security;

    public function __construct(
        EntityManagerInterface $entityManagerInterface,
        CompteRenduRepository $compteRenduRepository,
        Security $security)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->compteRenduRepository = $compteRenduRepository;
        $this->security = $security;
    }
    

    #[Route('/liste', name: 'compteRendu.liste')]
    public function listeCompteRendu(): Response
    {
        $listeCompteRendu = $this->compteRenduRepository->findAllWithUser($this->security->getUser());
        
        return $this->render('compteRendu/liste.html.twig', [
            'listeCompteRendu' => $listeCompteRendu,
        ]);
    }


    #[Route('/nouveau', name: 'compteRendu.nouveau')]
    public function nouveauCompteRendu(Request $request): Response
    {
        $compteRendu = new CompteRendu();
        $presentation = new Presentation();
        $presentation->setCompteRendu($compteRendu);
        $compteRendu->addPresentation($presentation);

        $form = $this->createForm(CompteRenduType::class, $compteRendu);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user = $this->security->getUser();
            $compteRendu->setUser($user);

            $this->entityManagerInterface->persist($compteRendu);

            foreach($compteRendu->getPresentation() as $presentation){
                $this->entityManagerInterface->persist($presentation);
            }

            $this->entityManagerInterface->flush();
            
            $this->addFlash('notice', "Votre compte-rendu a été enregistré avec succès.");
            return $this->redirectToRoute('compteRendu.nouveau');
        }

        return $this->render('compteRendu/nouveau.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
