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

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
    public function AjouterActuRequest(Request $request,  EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {   $document = new Document();
        $form_document = $this->createForm(DocumentFormType::class,$document);
        $form_document -> handleRequest($request);
    
        if( $form_document->isSubmitted() && $form_document->isValid()){

            $brochureFile = $form_document->get('PDF')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }
            
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
