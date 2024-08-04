<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class TestOpenAiController extends AbstractController{

    public function __construct(
        private ParameterBagInterface $parameterBag,
    )
    {
        
    }
    
    #[Route('/test-openai', name: 'test_openai')]
    public function testOpenAi(): Response
    {
        // $apiKey = 'votre_clé_API_OpenAI'; // Remplacez par votre clé API
        $apiKey = $this->parameterBag->get('OPENAI_API_KEY');
        $prompt = 'Écrivez une courte histoire sur un chat qui apprend à voler.';

        // Data structure for the chat model endpoint
        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens' => 100,
            'temperature' => 0.7,
        ];

        $ch = curl_init();

        // Correct endpoint for chat models
        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey,
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Désactivation de la vérification SSL (non recommandé pour la production)
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $errorMessage = 'Erreur de requête cURL : ' . curl_error($ch);
            curl_close($ch);
            return new JsonResponse(['error' => $errorMessage], Response::HTTP_INTERNAL_SERVER_ERROR);
        } else {
            // Decode JSON response
            $decodedResponse = json_decode($response, true);
            curl_close($ch);

            // Check for response validity and errors
            if (isset($decodedResponse['choices'][0]['message']['content'])) {
                return new JsonResponse(['response' => $decodedResponse['choices'][0]['message']['content']], Response::HTTP_OK);
            } else {
                // Log raw response for debugging
                return new JsonResponse(['error' => 'Une erreur est survenue dans la réponse de l\'API.', 'raw_response' => $decodedResponse], Response::HTTP_BAD_REQUEST);
            }
        }
    }
}