<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Orhanerday\OpenAi\OpenAi;
use Symfony\Component\Serializer\Encoder\JsonDecode;

class OpenAiService{
    public function __construct(
        private ParameterBagInterface $parameterBag,
    )
    {
        
    }
    public function getDestination(string $destination): string
    {
        
     
        $openai_api_key = $this->parameterBag->get('OPENAI_API_KEY');
        $open_ai = new OpenAi($openai_api_key);

        $complete = $open_ai->completion([
            'model' => 'text-davinci-003',
            'prompt' => 'En tant qu\'expert Guide touristique, fais moi un planning sur mes 2 semaines de vacances en me trouvant des choses à faire en me faisant une liste. Reviens à la ligne et saute une ligne à chaque nouveau jour. Donne un maximum de détail en citant la ville(s), le(s) restaurant(s), la plage(s). Pour un voyage de 2 semaines à'.$destination,
            // 'prompt' => 'Trouve moi des choses à voir pour mon voyage à {{destination}}. Pour ce voyage il y a {nbr_personne} pour {nbr_jour} jours. On a une aversion pour {aversion}.', Ajoute les variables dans les parametres de la fonction sendMessage
            'temperature' => 0,
            'max_tokens' => 3500,
            'frequency_penalty' => 0.5,
            'presence_penalty' => 0,
        ]);

        $json = json_decode($complete, true);

        if (isset($json['choices'][0]['text'])) {
            $json = $json['choices'][0]['text'];

            return $json;
        }

        $json = 'Une erreur est survenue !';

        return $json;
    }
}