<?php

namespace App\Service;

use GuzzleHttp\Client;

class OpenAIChatService
{
    private $httpClient;
    private $openaiApiKey;

    public function __construct(string $openaiApiKey) /*Client $httpClient*/
    {
        // $this->httpClient = $httpClient;
        $this->openaiApiKey = $openaiApiKey;
    }

    public function sendMessage(string $message): string
    {
        $response = $this->httpClient->post('chat/completions', [
            'json' => [
                'prompt' => $message,
                'max_tokens' => 50, // Le nombre maximum de tokens dans la réponse
            ],
        ]);

        // $openai_api_key = $this->parameterBag->get('OPENAI_API_KEY');
        // $open_ai = new OpenAIChatService($openai_api_key);

        // $complete = $open_ai->completion([
        //     'model' => 'text-davinci-003',
        //     'prompt' => $message,
        //     // 'prompt' => 'Trouve moi des choses à voir pour mon voyage à {{destination}}. Pour ce voyage il y a {nbr_personne} pour {nbr_jour} jours. On a une aversion pour {aversion}.', Ajoute les variables dans les parametres de la fonction sendMessage
        //     'temperature' => 0,
        //     'max_tokens' => 3500,
        //     'frequency_penalty' => 0.5,
        //     'presence_penalty' => 0,
        // ]);

        // dd($complete);

        $data = json_decode($response->getBody(), true);

        return $data['choices'][0]['text'] ?? '';
    }
}
