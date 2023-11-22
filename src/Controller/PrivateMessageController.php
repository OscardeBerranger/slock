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

    #[Route('/create/{id}', name: 'private_message_create')]
    public function create($id, PrivateConversationRepository $repository, Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, ImagesProcessor $processor){
        $privateConversation = $repository->find($id);
        $author = $this->getUser()->getProfile();
        $message = $serializer->deserialize($request->getContent(), PrivateMessage::class, 'json');
        $message->setAuthor($author);
        $message->setPrivateConversation($privateConversation);
        $message->setCreatedAt(new \DateTimeImmutable());

        $associatedImages = $message->getAssociatedImages();
        if ($associatedImages){
            foreach($processor->getImagesFromImageIds($associatedImages) as $image){
                $message->addImage($image);
            }
        }
        $manager->persist($message);
        $manager->flush();

        return $this->json("Message enregistré", 201);
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




    #[Route('/addimage/{idMessage}/{idImage}', name: 'add_image_to_private_message', methods: 'POST')]
    public function addImage(EntityManagerInterface $manager,
             #[MapEntity(mapping: ['idMessage'=>'id'])]PrivateMessage $message,
             #[MapEntity(mapping: ['idImage'=>'id'])]Image $image,
    ){
        dd($image);
        $message->addImage($image);
        $manager->persist($message);
        $manager->flush();
        return $this->json("The image has been added to your message", 201);
    }
}
