<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\OpenAIChatService;
use Symfony\Component\HttpFoundation\Request;

class ChatBotController extends AbstractController
{
    #[Route('/chat-bot', name: 'app_chat_bot')]
    public function chatBot(Request $request, OpenAIChatService $openAIChatService): Response
    {

        $message = $request->get('message');
        $response = $openAIChatService->sendMessage($message);
        
        return $this->render('chat_bot/chatBot.html.twig', [
            'response' => $response,
            'controller_name' => 'HomeController',
        ]);
    }
}
