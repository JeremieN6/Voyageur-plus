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
        
     
        $openai_api_key = $this->parameterBag->get('OPENAI_API_KEY');
        $open_ai = new OpenAi($openai_api_key);

        $complete = $open_ai->completion([
            'model' => 'text-davinci-003',
            'prompt' => 'En tant qu\'expert Guide touristique.
            Fais moi un planning sur mon séjour dont la destination est :' .$destination.' et qui vas durer'.$duree_sejour.'jours.
            Le nombre de voyageur pour ce séjour est de'.$nombre_personne_sejour.'personnes.
            Le budget par personne est de'.$budget_sejour.'.
            Pendant mon voyage la saison de la destination sera en : '.$saison_destination.'
            Pendant ce voyage le moyen de déplacement sera exclusivement en : '.$mobilite_sejour.'
            Je vais pour finir te donner une liste de mes préférence lorsque je voyage : '.implode(',', $interet_preference).'.
            et une liste de toute les choses dont je ne veux pas entendre parler pendant mon séjour : '.implode(',', $restrictions).'
            En te basant sur les élements que je t\'ai fourni, rédige moi une liste en revenant à la ligne pour chaque nouveau jour.',
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