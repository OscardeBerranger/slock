<?php

namespace App\Controller;

use App\Entity\GroupConversation;
use App\Entity\Image;
use App\Entity\PrivateConversation;
use App\Entity\PrivateMessage;
use App\Entity\Profile;
use App\Repository\PrivateConversationRepository;
use App\Repository\PrivateMessageRepository;
use App\Repository\ProfileRepository;
use App\Services\ImagesProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/api/private/message')]
class PrivateMessageController extends AbstractController
{

    #[Route('/create/{id}', name: 'private_message_create', methods: 'POST')]
    public function create($id, PrivateConversationRepository $repository, Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, ImagesProcessor $processor){





        $privateConversation = $repository->find($id);
        $author = $this->getUser()->getProfile();
        $message = $serializer->deserialize($request->getContent(), PrivateMessage::class, 'json');
        $message->setAuthor($author);
        $message->setPrivateConversation($privateConversation);
        $message->setCreatedAt(new \DateTimeImmutable());

//        $associatedImages = $message->getAssociatedImages();
//        if ($associatedImages){
//            foreach($processor->getImagesFromImageIds($associatedImages) as $image){
//                $message->addImage($image);
//            }
//        }
        $manager->persist($message);
        $manager->flush();

        return $this->json($message, 201, [], ["groups"=>"message:read"]);
    }

    #[Route('/edit/{id}', name: 'private_message_edit', methods: 'POST')]
    public function edit(PrivateMessage $message, SerializerInterface $serializer, Request $request, EntityManagerInterface $manager){
        if (!$message){
            return $this->json("message not found", "401");
        }
        $message->setContent($serializer->deserialize($request->getContent(), PrivateMessage::class, 'json')->getContent());
        $manager->persist($message);
        $manager->flush();
        return $this->json($message, 201, [], ["groups"=>"message:read"]);
    }

    #[Route('/delete/{idMessage}', name: 'private_message_delete', methods: 'DELETE')]
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
        return $this->json('An error has occured', 400);
    }

}
