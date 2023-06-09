<?php

namespace App\Controller;

use App\Form\QuestionsType;
use App\Service\OpenAiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ChatBotController extends AbstractController
{
    #[Route('/chat-bot', name: 'app_chat_bot')]
    public function chatBot(Request $request, OpenAiService $openAi): Response
    {


        $form = $this->createForm(QuestionsType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $json = $openAi->getDestination($data['destination']);
            return $this->render('chat_bot/chatBot.html.twig', [
                'form' => $form->createView(),
                'json' => $json ?? null,
            ]);
        }   
        // $message = $request->get('message');
        // $response = $openAIChatService->sendMessage($message);
        
        return $this->render('chat_bot/chatBot.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'HomeController',
            'json' => $json ?? null,
        ]);
    }
}
