<?php

namespace App\Controller;

use App\Entity\FriendRequest;
use App\Entity\Profile;
use App\Entity\Relation;
use App\Repository\FriendRequestRepository;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class FriendRequestController extends AbstractController
{
    #[Route('/friend', name: 'app_friend')]
    public function index(): Response
    {
        return $this->render('friend/index.html.twig', [
            'controller_name' => 'FriendRequestController',
        ]);
    }


    #[Route('/api/sendfriendrequest/{id}', name: 'send_friend_request')]
    public function send($id, ProfileRepository $repository ,Request $request, SerializerInterface $serializer, EntityManagerInterface $manager){
        $sender = $this->getUser()->getProfile();
        $recipient = $repository->find($id);
        $frequest = new FriendRequest();
        $frequest->setSentBy($sender);
        $frequest->setReceivedBy($recipient);
        $frequest->setCreatedAt(new \DateTimeImmutable());
        $frequest->setStatus('pending');
        $manager->persist($frequest);
        $manager->flush();
        return $this->json($frequest, 200, [], ['groups'=>'request:read']);
    }


    #[Route('/api/acceptFriendRequest/{id}', name:'accept_friend_request')]
    public function accept($id,FriendRequestRepository $repository, EntityManagerInterface $manager){
        $frequest = $repository->find($id);
        $u1 = $frequest->getSentBy();
        $u2 = $frequest->getReceivedBy();
        $relation = new Relation();
        $relation->setRelationAsSender($u1);
        $relation->setRelationAsRecipient($u2);
        $frequest->setStatus('accepted');
        $manager->persist($frequest);
        $manager->persist($relation);
        $manager->flush();
        return $this->json('You have a new friend how wonderfull :)', 200);
    }

    #[Route('/api/declineFriendRequest/{id}', name:'decline_friend_request')]
    public function decline($id, FriendRequestRepository $repository, EntityManagerInterface $manager){
        $frequest = $repository->find($id);
        $frequest->setStatus('declined');
        $manager->persist($frequest);
        $manager->flush();
        return $this->json($frequest->getSentBy()->getUsername().' will not be your friend !', 200);
    }
}
