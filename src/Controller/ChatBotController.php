<?php

namespace App\Controller;

use App\Entity\Reponses;
use App\Entity\Question;
use App\Entity\Questions;
use App\Entity\Subscription;
use App\Form\QuestionsType;
use App\Repository\QuestionsRepository;
use App\Repository\ReponsesRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\UsersRepository;
use App\Service\OpenAiService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ChatBotController extends AbstractController
{
    #[Route('/generate-plan', name: 'app_generate_plan')]
    public function chatBot(
        QuestionsRepository $questionsRepository,
        Request $request,
        OpenAiService $openAi,
        EntityManagerInterface $entityManager,
        ReponsesRepository $reponsesRepository,
        SubscriptionRepository $subscriptionRepository,
        \MercurySeries\FlashyBundle\FlashyNotifier $flashy
    ): Response {

        $connectedUser = $this->getUser();

        if($connectedUser){
            $activeSubscriptions = $subscriptionRepository->hasActiveSubscription($connectedUser);
            $formSubmissionLimit = 3;
            $formSubmissionsNumber = $reponsesRepository->countDistinctFormNumbersByUser($connectedUser);

            // Vérifiez si l'utilisateur est un administrateur
            if (in_array('ROLE_ADMIN', $connectedUser->getRoles())) {
                $formSubmissionLimit = PHP_INT_MAX; // Limite illimitée pour les admins
            }
        
            if (count($activeSubscriptions) > 0 || $formSubmissionsNumber < $formSubmissionLimit) {

                $form = $this->createForm(QuestionsType::class);
                $form->handleRequest($request);

                //On vérifie que le formulaire est soumis et valide 
                if ($form->isSubmitted() && $form->isValid()) {
                    $data = $form->getData();
                    $interet_preference = explode(',', $data['interet_preference']);
                    $restrictions = explode(',', $data['restrictions']);
                    $json = $openAi->getDestination(
                        $data['destination'],
                        $data['duree_sejour'],
                        $data['nombre_personne_sejour'],
                        $data['budget_sejour'],
                        $data['mobilite_sejour'],
                        $data['saison_destination'],
                        $interet_preference,
                        $restrictions
                    );
                    // On test la réponse de l'API 
                    dd($json);

                    //On récupère le user connecté
                    $user = $this->getUser();

                    //On envoie les données du formulaire en base si le user est connecté
                    if ($user) {

                        // Obtenir un chiffre aléatoire entre 0 et 9
                        $randomDigit = mt_rand(0, 999);

                        // Créer la clé complète en combinant les parties
                        $formKey = 'FRM#' . substr($randomDigit, -4);
                        
                        // Étape 1 : Récupérer les questions existantes depuis la base de données
                        $questions = $questionsRepository->findAll();

                        // Créer un tableau pour stocker les IDs des questions
                        $questionIdsArray = [];

                        // Parcourir les questions et ajouter leurs IDs au tableau
                        foreach ($questions as $question) {
                            $questionIdsArray[] = $question->getId();
                        }
                        // On récupère les réponses soumises depuis le formulaire
                        $reponsesData = $form->getData();

                        $destination = $reponsesData['destination'];
                        $dureeSejour = $reponsesData['duree_sejour'];
                        $nombrePersonnes = $reponsesData['nombre_personne_sejour'];
                        $budget_sejour = $reponsesData['budget_sejour'];
                        $mobilite_sejour = $reponsesData['mobilite_sejour'];
                        $saison_destination = $reponsesData['saison_destination'];
                        $interet_preference = $reponsesData['interet_preference'];
                        $restrictions = $reponsesData['restrictions'];

                        $finalData = [
                            $destination, $dureeSejour, $nombrePersonnes, $budget_sejour, 
                            $mobilite_sejour, $saison_destination, $interet_preference, $restrictions
                        ];

                        // Supposons que vous avez deux tableaux : $questionIdsArray et $reponsesData

                        // Créer un tableau pour stocker les associations question-réponse
                        $associations = [];

                        // Ajouter un élément vide au début du tableau $finalData pour que l'index 0 corresponde à la première question
                        // J'ai choisi de récupérer uniquement les réponses aux questions en les rangeant dans un tableau mais ça fonctionne également directement avec le tableau clé valeur $reponsesData
                        array_unshift($finalData, '');

                        foreach ($questionIdsArray  as $questionId) {
                            // Récupérer l'ID de la question
                            // $questionId = $question->getId();
                            $fieldName = $question->getNomQuestion();
                            $reponse = $form->get($fieldName)->getData();

                            // Vérifier si la réponse pour cette question existe dans le tableau des réponses
                            if (array_key_exists($questionId, $finalData)) {
                                // Récupérer la réponse correspondante
                                $reponse = $finalData[$questionId];

                                // Associer l'ID de la question à la réponse dans le tableau d'associations
                                $associations[$questionId] = $reponse;
                            }
                        }
                        // dd($questionIdsArray, $finalData, $associations, $questionId);
                        // Maintenant, vous avez un tableau $associations qui contient les associations entre IDs de question et réponses

                        // Parcourir les associations et enregistrer les réponses en base de données
                        foreach ($associations as $questionId => $reponse) {
                            // Récupérer l'objet Question depuis la base de données
                            $question = $questionsRepository->find($questionId);

                            if ($question) {
                                // Créer une nouvelle instance de Réponses
                                $reponseEntity = new Reponses();
                                $reponseEntity->setQuestion($question);
                                $reponseEntity->setLaReponse($reponse);
                                $reponseEntity->setUser($user);
                                $reponseEntity->setFormNumber($formKey);
                                // $reponseEntity->setReponseIA($json);
                                $reponseEntity->setCreatedAt(new DateTimeImmutable());

                                // Enregistrer la réponse en base de données
                                $entityManager->persist($reponseEntity);
                            }
                        }

                        $entityManager->flush();


                        $flashy->success('Envoyé à l\'AI & à la base de donné ✅. Vas voir !');
                        return $this->render('IAFolder/reponseIA.html.twig', [
                            'form' => $form->createView(),
                            'user' => $connectedUser,
                            'json' => $json ?? null,
                        ]);
                    }
                    $flashy->success('Un problème est survenu lors de l\'envoie... ⛔ !');
                }
            } else {
                 // Redirigez l'utilisateur vers la page d'abonnement et affichez un message flash
                $flashy->error('Vous devez souscrire à un abonnement pour continuer à utiliser l\'outil.');

                // Redirection vers la page d'abonnement
                return $this->redirectToRoute('abonnement');
            }
        }
        else{
            $flashy->warning('Attention, vous devez être connecté, ou vous inscrire pour utiliser l\'outil.');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('IAFolder/formGPT.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'HomeController',
            'user' => $connectedUser,
            'json' => $json ?? null,
        ]);
    }
}
