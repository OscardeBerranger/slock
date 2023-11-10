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
    public function send($id, ProfileRepository $repository ,Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, FriendRequestRepository $friendRequestRepository){
        $sender = $this->getUser()->getProfile();
        $recipient = $repository->find($id);
        if (!$recipient){
            return $this->json('The user does not exist', 400);
        }
        if ($sender == $recipient){
            return $this->json('Unfortunately you cannot be friend with yourself', 400);
        }
        if ($sender->isMyFriend($recipient)){
            return $this->json('You two are already friends', 400);
        }
        $frequest = new FriendRequest();
        $frequest->setSentBy($sender);
        $frequest->setReceivedBy($recipient);
        $frequest->setCreatedAt(new \DateTimeImmutable());
        $frequest->setStatus('pending');;
        $manager->persist($frequest);
        $manager->flush();
        return $this->json($frequest, 200, [], ['groups'=>'request:read']);
    }


    #[Route('/api/acceptFriendRequest/{id}', name:'accept_friend_request')]
    public function accept($id,FriendRequestRepository $repository, EntityManagerInterface $manager)
    {
        $frequest = $repository->find($id);
        $u1 = $frequest->getSentBy();
        $u2 = $frequest->getReceivedBy();
        if ($this->getUser()->getProfile() == $u1) {
            return $this->json("We know you really wanna be friend with " . $u2->getUsername() . " but you cannot accept a request for others.", 400);
        }
        if ($this->getUser()->getProfile() == $u2) {
            $relation = new Relation();
            $relation->setSender($u1);
            $relation->setRecipient($u2);
            $frequest->setStatus('accepted');
            $manager->persist($frequest);
            $manager->persist($relation);
            $manager->flush();
            return $this->json('You have a new friend how wonderfull :)', 200);
        }
        return $this->json('An error as occured', 400);
    }

    #[Route('/api/declinefriendrequest/{id}', name:'decline_friend_request')]
    public function decline($id, FriendRequestRepository $repository, EntityManagerInterface $manager){
        $frequest = $repository->find($id);
        if ($frequest->getReceivedBy()==$this->getUser()->getProfile()){
            $frequest->setStatus('declined');
            $manager->persist($frequest);
            $manager->flush();
            return $this->json($frequest->getSentBy()->getUsername().' will not be your friend !', 200);
        }
        return $this->json('An error as occured', 400);
    }
}
