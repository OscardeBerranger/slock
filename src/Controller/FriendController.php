<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FriendController extends AbstractController
{
    #[Route('/api/getmyfriends', name: 'get_my_friends')]
    public function getMyFriends(){
        $current = $this->getUser()->getProfile()->getFriendsList();
        return $this->json($current, 200, [], ['groups'=>"user:read"]);
    }
}
