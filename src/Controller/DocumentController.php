<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Document;
use App\Repository\DocumentRepository;
use App\Form\DocumentFormType;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class DocumentController extends AbstractController
{
    #[Route('/document', name: 'app_document')]
    public function index(): Response
    {
        return $this->render('document/index.html.twig', [
            'controller_name' => 'DocumentController',
        ]);
    }

    #[Route('/admin/document/ajout', name: 'document.add')]
    public function AjouterActuRequest(Request $request,  EntityManagerInterface $manager): Response
    {   $document = new Document();
        $form_document = $this->createForm(DocumentFormType::class,$document);
        $form_document -> handleRequest($request);
    
        if( $form_document->isSubmitted() && $form_document->isValid()){
            
            $manager->persist($document);
            $manager->flush();

            return $this->redirectToRoute('app_rappel',[
            ]);
        }

        return $this->render('document/index.html.twig', [
            'form_document' => $form_document->createView()
        ]);
    }
}
