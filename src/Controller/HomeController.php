<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\ContactType;
use App\Form\NewsletterType;
use App\Repository\NewsletterRepository;
use App\Repository\PlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        NewsletterRepository $newsletterRepository,
        \MercurySeries\FlashyBundle\FlashyNotifier $flashy): Response
    {

        $formNewsletter = $this->createForm(NewsletterType::class);
        $formNewsletter->handleRequest($request);

        if($formNewsletter->isSubmitted() && $formNewsletter->isValid()){
            $email = $formNewsletter->get('email')->getData();

            $emailExist = $newsletterRepository->findOneBy(['email' => $email]);

            if($emailExist){
                $flashy->warning('Cet e-mail est déjà inscrit à la Newsletter.');
                return $this->redirectToRoute('app_home');
            }else{
                $newsletterEmail = new Newsletter();
                $newsletterEmail->setEmail($email);
    
                $entityManager->persist($newsletterEmail);
                $entityManager->flush();
    
                $flashy->success('Vous avez été ajouté à la Newsletter !');
    
                return $this->redirectToRoute('app_home');
            }
        } elseif ($formNewsletter->isSubmitted() && !$formNewsletter->isValid()) {
            $flashy->error('Une erreur est survenue lors de l\'ajout à la Newsletter.');
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'formNewsletter' => $formNewsletter->createView()
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(
        Request $request, 
        MailerInterface $mailer,
        \MercurySeries\FlashyBundle\FlashyNotifier $flashy): Response
    {
        $contactForm = $this->createForm(ContactType::class);

        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()){
            $data = $contactForm->getData();

            $defaultEmail = 'contact@voyageur-plus.jeremiecode.fr'; // Adresse de l'expéditeur par défaut
            $senderName = $data['prenom'];
            $senderEmail = $data['email'];
            $emailMessage = 'Email envoyé par : ' . $senderName . "\n\nAdresse email : " .$senderEmail. "\n\n" . $data['message'];

            // Version original avec la récupération de l'email de la personne remplissant le formulaire de contact
            // $mailAdress = $data['email'];
            // $emailMessage = $data['message'];

            $email = (new Email())
                // ->from($mailAdress)
                ->from($defaultEmail)
                ->to('contact@voyageur-plus.jeremiecode.fr')
                // ->to('contact@voyageur-plus.fr')
                ->subject('Email reçu depuis la page contact de Voyageur +')
                ->text($emailMessage);

                $mailer->send($email);

                // Utilisez Flashy pour afficher un message flash de succès
                $flashy->success('Votre email a bien été envoyé ✅ !');

                // Redirigez l'utilisateur vers la même page (rafraîchissement)
                return $this->redirectToRoute('app_contact');
        }elseif ($contactForm->isSubmitted() && !$contactForm->isValid()) {
            $flashy->error('Une erreur est survenue lors de l\'envoie du mail. Veuillez réessayer.');
        }

        return $this->render('contact/contact.html.twig', [
            'controller_name' => 'HomeController',
            'contactForm' => $contactForm->createView()
        ]);
    }

    #[Route('/abonnement', name: 'abonnement')]
    public function abonnement(PlanRepository $planRepository): Response
    {
        $plan = $planRepository->findAll();

        return $this->render('plan/plans.html.twig', [
            'Plan' => $plan,
            'controller_name' => 'ParametresUserController',
        ]);
    }

    #[Route('/a-propos', name: 'about')]
    public function about(): Response
    {
        return $this->render('about/aboutUs.html.twig', [
            'controller_name' => 'ParametresUserController',
        ]);
    }

}
