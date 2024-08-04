<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenAiService {
    private $apiKey;
    private $httpClient;

    public function __construct(ParameterBagInterface $parameterBag, HttpClientInterface $httpClient)
    {
        $this->apiKey = $parameterBag->get('OPENAI_API_KEY');
        $this->httpClient = $httpClient;
    }

    public function getDestination(
        string $destination, 
        int $duree_sejour, 
        int $nombre_personne_sejour,
        int $budget_sejour,
        string $saison_destination,
        string $mobilite_sejour,
        array $interet_preference,
        array $restrictions): string
    {
        
    // Formater le prompt avec les informations fournies
    $prompt = sprintf(
        "En tant qu'expert Guide touristique. Fais moi un planning sur mon séjour dont la destination est : %s et qui vas durer %d jours. Le nombre de voyageur pour ce séjour est de %d personnes. Le budget par personne est de %d. Pendant mon voyage, la saison de la destination sera : %s. Pendant ce voyage, le moyen de déplacement sera exclusivement : %s. Voici mes préférences de voyage : %s. Voici les choses à éviter : %s. En te basant sur ces éléments, rédige une liste pour chaque jour de mon séjour.",
        $destination,
        $duree_sejour,
        $nombre_personne_sejour,
        $budget_sejour,
        $saison_destination,
        $mobilite_sejour,
        implode(', ', $interet_preference),
        implode(', ', $restrictions)
    );

    // Data structure for the chat model endpoint
    $data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => 'You are a helpful assistant.'],
            ['role' => 'user', 'content' => $prompt]
        ],
        'max_tokens' => 3500,
        'temperature' => 0.7,
    ];

    try {
        $response = $this->httpClient->request('POST', 'https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
            ],
            'json' => $data,
        ]);

        $content = $response->getContent();
        $decodedResponse = json_decode($content, true);

        if (isset($decodedResponse['choices'][0]['message']['content'])) {
            return $decodedResponse['choices'][0]['message']['content'];
        } else {
            return 'Une erreur est survenue dans la réponse de l\'API.';
        }
    } catch (\Exception $e) {
        return 'Erreur lors de la requête à l\'API : ' . $e->getMessage();
    }
}
}