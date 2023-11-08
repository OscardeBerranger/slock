<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FriendController extends AbstractController
{
    #[Route('/friend', name: 'app_friend')]
    public function index(): Response
    {
        return $this->render('friend/index.html.twig', [
            'controller_name' => 'FriendController',
        ]);
    }

    #[Route('/api/getmyfriends', name: 'get_my_friends')]
    public function getMyFriends(){
        $current = $this->getUser()->getProfile()->getFriendsList();
        return $this->json($current, 200, [], ['groups'=>"user:read"]);
    }
}
