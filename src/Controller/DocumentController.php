<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Document;
use App\Repository\DocumentRepository;
use App\Form\DocumentFormType;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class DocumentController extends AbstractController
{
    #[Route('/document', name: 'app_document')]
    public function index(DocumentRepository $Doc): Response
    {
        $showDoc = $Doc->findBy([],['id' => 'DESC']);

        return $this->render('document/Accueil.html.twig', [
            'controller_name' => 'DocumentController',
            'showDoc' => $showDoc,
        ]);
    }

    #[Route('/admin/document/ajout', name: 'document.add')]
    public function AjouterDocRequest(Request $request,  EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {   $document = new Document();
        $form_document = $this->createForm(DocumentFormType::class,$document);
        $form_document -> handleRequest($request);
    
        if( $form_document->isSubmitted() && $form_document->isValid()){

            $brochureFile = $form_document->get('titre')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $document->setTitre($newFilename);
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

    #[Route('/admin/rappel', name: 'document.show')]
    public function findByDate(ManagerRegistry $registry): Response
    {       $currentDate = new \DateTime();
            $currentDate2 = new \DateTime(); 
        
            $currentDate->add(new \DateInterval('P4D'));
            $currentDate2->sub(new \DateInterval('P4D'));

            $doc = $registry->getManager()->getRepository(Document::class)->createQueryBuilder('d')
            ->select('d.id, d.Titre, d.date_fin_valid, m.ref_mat as ref_mat')
            ->join('d.mat', 'm')
            ->where('d.date_fin_valid <= :currentDate and d.date_fin_valid >= :currentDate2') // Ajout de la clause WHERE
            ->setParameter('currentDate', $currentDate)
            ->setParameter('currentDate2', $currentDate2) // Paramètre pour comparer avec la date actuelle
            ->getQuery()
            ->getResult();
            return $this->render('rappel/index.html.twig', [
                'showdoc' => $doc
            ]);
    }
}
