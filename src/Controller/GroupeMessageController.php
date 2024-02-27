<?php

namespace App\Controller;

use App\Entity\GroupConversation;
use App\Entity\GroupeMessage;
use App\Services\GroupConversationServices;
use App\Services\ImagesProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/api/group/message')]
class GroupeMessageController extends AbstractController
{
    #[Route('/create/{id}', name: 'group_message_create', methods: 'POST')]
    public function create(GroupConversation $groupConversation, GroupConversationServices $services, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ImagesProcessor $processor){
        if (!$services->isInConv($this->getUser()->getProfile(), $groupConversation)){
            return $this->json("You are not in this conversation", 400);
        }
        $groupMessage = $serializer->deserialize($request->getContent(), GroupeMessage::class, 'json');
        $groupMessage->setGroupConversation($groupConversation);
        $groupMessage->setAuthor($this->getUser()->getProfile());
        $groupMessage->setCreatedAt(new \DateTimeImmutable());
        $associatedImages = $groupMessage->getAssociatedImages();
        if ($associatedImages){
            foreach($processor->getImagesFromImageIds($associatedImages) as $image){
                $groupMessage->addImage($image);
            }
        }
        $manager->persist($groupMessage);
        $manager->flush();
        return $this->json($groupMessage ,201, [], ["groups"=>"groupmessage:read"]);
    }

    #[Route('/get/{id}', name: 'group_messages_get_messages', methods: 'GET')]
    public function getAll(GroupConversation $conversation, GroupConversationServices $services, ImagesProcessor $processor){
        if (!$services->isInConv($this->getUser()->getProfile(), $conversation)){
            return $this->json("You are not in this conversation0, 400");
        }
        $messages = $processor->setImagesUrlsOfMessagesFromGroupConversation($conversation);

        return $this->json($messages, 200, [], ["groups"=>"groupmessage:read"]);
    }

}
