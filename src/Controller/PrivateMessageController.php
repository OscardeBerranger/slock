<?php

namespace App\Controller;

use App\Entity\PrivateMessage;
use App\Repository\PrivateConversationRepository;
use App\Repository\PrivateMessageRepository;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PrivateMessageController extends AbstractController
{
    #[Route('/private/message', name: 'app_private_message')]
    public function index(): Response
    {
        return $this->render('private_message/index.html.twig', [
            'controller_name' => 'PrivateMessageController',
        ]);
    }

    #[Route('/api/createprivatemessage/{id}', name: 'private_message_create')]
    public function create($id, PrivateConversationRepository $repository, Request $request, SerializerInterface $serializer, EntityManagerInterface $manager){
        $privateConversation = $repository->find($id);
        $author = $this->getUser()->getProfile();
        $message = $serializer->deserialize($request->getContent(), PrivateMessage::class, 'json');
        $message->setAuthor($author);
        $message->setPrivateConversation($privateConversation);
        $message->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($message);
        $manager->flush();

        return $this->json($message, 200, [], ["groups"=>"message:read"]);
    }


    #[Route('/api/deleteprivatemessage/{idMessage}', name: 'private_message_delete')]
    public function delete($idMessage, ProfileRepository $profileRepository, PrivateMessageRepository $privateMessageRepository, EntityManagerInterface$manager){
        $message = $privateMessageRepository->find($idMessage);
        if (!$message){return $this->json('Message not found', 400);}
        $cuser = $this->getUser()->getProfile();
        $msender = $message->getAuthor();

        if ($cuser == $msender){
            $manager->remove($message);
            $manager->flush();
            return $this->json('Message deleted', 200);
        }
        return $this->json('An error as occured', 400);
    }
}
