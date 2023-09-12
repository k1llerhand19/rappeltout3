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

}
