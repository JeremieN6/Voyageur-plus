<?php

namespace App\Controller;

use App\Repository\ReponsesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatBotController extends AbstractController
{
    #[Route('/mon-compte', name: 'myAccount')]
    public function chatBot(): Response {

        $connectedUser = $this->getUser();

        return $this->render('userAccount/myAccount.html.twig', [
            'controller_name' => 'HomeController',
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
