<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Materiel;
use App\Repository\MaterielRepository;
use App\Form\MaterielFormType;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class MaterielController extends AbstractController
{
    #[Route('/admin/materiel/ajout', name: 'materiel.add')]
    public function AjouterActuRequest(Request $request,  EntityManagerInterface $manager): Response
    {   $materiel = new Materiel();
        $form_materiel = $this->createForm(MaterielFormType::class,$materiel);
        $form_materiel -> handleRequest($request);
    
        if( $form_materiel->isSubmitted() && $form_materiel->isValid()){
            
            $manager->persist($materiel);
            $manager->flush();

            return $this->redirectToRoute('app_rappel',[
            ]);
        }
        return $this->render('materiel/index.html.twig', [
            'form_materiel' => $form_materiel->createView()
        ]);
    }
    #[Route('/materiel', name: 'app_materiel')]
    public function index(MaterielRepository $Mat): Response
    {
        $showMat = $Mat->findBy([],['id' => 'DESC']);

        return $this->render('materiel/Accueil.html.twig', [
            'controller_name' => 'DocumentController',
            'showMat' => $showMat,
        ]);
    }

    #[Route('/admin/materiel/sup/{id}', name: 'materiel.delete', methods: ['GET', 'POST'])]
    public function delete(Materiel $mat, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$mat->getId(), $request->get('_token')))
        {
            $entityManager->remove($mat);
            $entityManager->flush();

        }

        return $this->redirectToRoute('app_materiel');
    }

    #[Route('/admin/materiel/{id}', name: 'materiel.edit', methods: ['GET', 'POST'])]
    public function Modifier(Materiel $mat, Request $request, EntityManagerInterface $manager): Response
    {          
        $form_mat = $this->createForm(MaterielFormType::class, $mat);
        $form_mat->handleRequest($request);

        if ($form_mat->isSubmitted() && $form_mat->isValid()) {
            $manager->persist($mat);
            $manager->flush();

            // Rediriger vers une page de confirmation ou une autre action
            return $this->redirectToRoute('app_materiel',[
            ]);
        }

        return $this->render('materiel/modifier.html.twig', [
            'form_mat' => $form_mat->createView(),
        ]);
    }

}
