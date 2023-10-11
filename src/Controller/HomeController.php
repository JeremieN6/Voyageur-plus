<?php

namespace App\Controller;

use App\Entity\Test;
use App\Form\ContactType;
use App\Form\TestType;
use App\Repository\PlanRepository;
use App\Repository\TestRepository;
use App\Repository\UsersRepository;
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
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
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

            $mailAdress = $data['email'];
            $emailMessage = $data['message'];

            $email = (new Email())
                ->from($mailAdress)
                ->to('contact@voyageur-plus.fr')
                ->subject('Email de contact')
                ->text($emailMessage);

                $mailer->send($email);

                // Utilisez Flashy pour afficher un message flash de succès
                $flashy->success('Votre email a bien été envoyé ✅ !');

                // Redirigez l'utilisateur vers la même page (rafraîchissement)
                return $this->redirectToRoute('app_contact');
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
