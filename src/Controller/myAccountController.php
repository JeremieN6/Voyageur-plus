<?php

namespace App\Controller;

use App\Repository\NewsletterRepository;
use App\Repository\ReponsesRepository;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserFormType;

class myAccountController extends AbstractController
{   
    #[Route('/mon-compte', name: 'myAccount')]
    public function chatBot(
        ReponsesRepository $reponsesRepository,
        SubscriptionRepository $subscriptionRepository,
        NewsletterRepository $newsletterRepository
    ): Response {

        $connectedUser = $this->getUser();

        // Récupérer le nombre de formulaire distincts
        $distinctFormNumbers = $reponsesRepository->countDistinctFormNumbersByUser($connectedUser);

        //On check si l'utilisateur actuel à un abonnement ou non
        if ($connectedUser) {
            $subscriptions = $connectedUser->getSubscriptions();
            $hasActiveSubscription = false;
            foreach ($subscriptions as $subscription) {
                if ($subscription->isIsActive()) {
                    $hasActiveSubscription = true;
                    break; // Vous pouvez sortir de la boucle si un abonnement actif est trouvé
                }
            }
        }

        //Je check si l'addresse mail du user Connecté est dans la liste des Newsletter
        $emailConnectedUser = $connectedUser->getEmail();

        $NewsletterSubscriber = $newsletterRepository->findOneBy(['email'=>$emailConnectedUser]);

        $isOnNewsletterSubscriber = false;
        if ($NewsletterSubscriber) {
            // L'email de l'utilisateur est dans la liste de la newsletter
           $isOnNewsletterSubscriber = true;
        } else {
            // L'email de l'utilisateur n'est pas dans la liste de la newsletter
            $isOnNewsletterSubscriber = false;
        }

        $subscription = null;

        if ($connectedUser) {
            // Supposons que l'entité Subscription est associée à l'utilisateur via une relation
            $subscriptions = $connectedUser->getSubscriptions();

            // Vérifiez si l'un des abonnements est actif
            foreach ($subscriptions as $sub) {
                if ($sub->isIsActive()) {
                    $subscription = $sub;
                    break; // Sortez de la boucle dès qu'un abonnement actif est trouvé
                }
            }
        }

        $startDate = null;
        $endDate = null;
        $planName = null;
        if ($subscription) {
            $startDate = $subscription->getCurrentPeriodStart()->format('d/m/Y à H:i');
            $endDate = $subscription->getCurrentPeriodEnd()->format('d/m/Y à H:i');
            $plan = $subscription->getPlan();
            if($plan){
                $planName = $plan->getNom();
            }
            // Maintenant, $startDate, $endDate et $planName contiennent les informations de l'abonnement actif.
            // Vous pouvez les utiliser selon vos besoins.
        } else {
            // L'utilisateur n'a pas d'abonnement actif.
        }

        return $this->render('userAccount/myAccount.html.twig', [
            'controller_name' => 'HomeController',
            'connectedUser' => $connectedUser,
            'distinctFormNumbers' => $distinctFormNumbers,
            'hasActiveSubscription' => $hasActiveSubscription,
            'isOnNewsletterSubscriber' => $isOnNewsletterSubscriber,
            'startDate' => $startDate, 'endDate' => $endDate, 'planName' => $planName
        ]);
    }

    
    #[Route('/modification-profil', name: 'modif_profil')]
    public function modificationProfil(
        Request $request, 
        EntityManagerInterface $em,
        \MercurySeries\FlashyBundle\FlashyNotifier $flashy): Response
    {

        //On récupère l'utilisateur connecté
        $connectedUser = $this->getUser();
        //On crée le formulaire
        $userForm = $this->createForm(UserFormType::class, $connectedUser);
        //On traite la requête du formulaire
        $userForm->handleRequest($request);


        //On vérifie si le formulaire est soumis ET valide
        if($userForm->isSubmitted() && $userForm->isValid()){

            //envoie a l'entité
            $em->persist($connectedUser);
            $em->flush();

            $flashy->success('Ton profil a été modifié avec succès ! 🚀');

            //On redirige
            return $this->redirectToRoute('myAccount');
        }

        // dd($userForm);

        return $this->render('userAccount/modification_profil.html.twig', [
            'controller_name' => 'HomeController',
            'userForm' => $userForm->createView(),
            'connectedUser' => $connectedUser
        ]);
    }
    
    #[Route('/mes_voyages', name: 'app_voyages')]
    public function mesVoyages(
        ReponsesRepository $reponsesRepository,
    ): Response
    {
        //Récupérer l'Utilisateur connecté
        $connectedUser = $this->getUser();

        //Voir s'il y a des réponses pour l'utilisateur connecté dans la table Reponses 
        if (!$connectedUser) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }
        else{
            //Récupérer toutes les lignes de la table Reponses
            $mesVoyages = $reponsesRepository->findBy(['user' => $connectedUser]);
        }


        $connectedUser = $this->getUser();

        if (!$connectedUser) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }
        else{
        // Récupérer tous les numéros de formulaire distincts
        $distinctFormNumbers = $reponsesRepository->findDistinctFormNumbersByUser($connectedUser);

        // Initialiser un tableau pour stocker les réponses par numéro de formulaire
        $responsesByForm = [];

        // Boucler sur chaque numéro de formulaire
        foreach ($distinctFormNumbers as $formNumberArray) {

            // Obtenir la valeur "formNumber" du tableau associatif
            $formNumber = $formNumberArray["formNumber"];

            // Récupérer les réponses associées à ce numéro de formulaire
            $responsesForForm = $reponsesRepository->findByFormNumberCustomAndUser($formNumber, $connectedUser);
            // $testNumber = $formNumber["formNumber"];

            // Stocker les réponses dans le tableau
            $responsesByForm[$formNumber] = $responsesForForm;
        }

        }        

        return $this->render('userAccount/mesVoyages.html.twig', [
            'controller_name' => 'HomeController',
            'user' => $connectedUser,
            'mesVoyages' => $mesVoyages,
            'responsesByForm' => $responsesByForm,
        ]);
    }
}
