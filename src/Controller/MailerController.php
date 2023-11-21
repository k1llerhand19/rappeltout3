<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;


use App\Entity\Document;
use App\Repository\DocumentRepository;
use App\Form\DocumentFormType;

use App\Form\ContactType;



class MailerController extends AbstractController
{
    
    #[Route('/admin/mailer', name: 'app_mailer')]
    public function sendEmail(MailerInterface $mailer, ManagerRegistry $registry): Response
    {       $currentDate = new \DateTime();
            $currentDate2 = new \DateTime(); 
        
            $currentDate->add(new \DateInterval('P4D'));
            $currentDate2->sub(new \DateInterval('P4D'));

            $doc = $registry->getManager()->getRepository(Document::class)->createQueryBuilder('d')
            ->select('d.id, d.Titre, d.date_fin_valid, m.ref_mat as ref_mat, m.nom_mat as nom_mat')
            ->join('d.mat', 'm')
            ->where('d.date_fin_valid <= :currentDate and d.date_fin_valid >= :currentDate2') // Ajout de la clause WHERE
            ->setParameter('currentDate', $currentDate)
            ->setParameter('currentDate2', $currentDate2) // Paramètre pour comparer avec la date actuelle
            ->getQuery()
            ->getResult();
        
            $email = (new TemplatedEmail())
            ->from('admin.BtsSioAubusson@gmail.com')
            ->to('Toi.Toi@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Expiration de materiel')
            ->htmlTemplate('rappel/index.html.twig')
            ->context(['showdoc' => $doc]);

            $mailer->send($email);

            return $this->render('rappel/index.html.twig', [
                'showdoc' => $doc
            ]);
    }
}