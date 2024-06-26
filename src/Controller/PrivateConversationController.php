<?php

namespace App\Controller;

use App\Entity\PrivateConversation;
use App\Entity\PrivateMessage;
use App\Entity\Profile;
use App\Repository\PrivateConversationRepository;
use App\Repository\ProfileRepository;
use App\Services\ImagesProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/api/private/conversation')]
class PrivateConversationController extends AbstractController
{


    #[Route('/get/{id}', name: 'get_private_messages', methods: 'GET')]
    public function get(PrivateConversation $conversation, ImagesProcessor $processor){
        return $this->json($processor->setImagesUrlsOfMessagesFromPrivateConversation($conversation), 200, [], ["groups"=>"message:read"]);
    }

    #[Route('/create/{id}', name: 'private_converstation_create', methods: 'POST')]
    public function create($id, ProfileRepository $profileRepository, EntityManagerInterface $manager){
        $u1 = $this->getUser()->getProfile();
        $u2 = $profileRepository->find($id);
        $conv = new PrivateConversation();
        $conv->setConvCreator($u1);
        $conv->setConvRecipient($u2);
        $manager->persist($conv);
        $manager->flush();
        return $this->json($conv, 200, [], ["groups"=>"conv:read"]);
    }

    #[Route('/getmyconvs', name: 'private_conversation_get_my_conv', methods: 'GET')]
    public function getMyConvs(PrivateConversationRepository $repository){
        $final = [];
        $tmp = $repository->findBy(["convCreator"=>$this->getUser()]);
        $tmp1 = $repository->findBy(["convRecipient"=>$this->getUser()]);
        foreach ($tmp as $item){
            $final[]=$item;
        }
        foreach ($tmp1 as $item){
            $final[]=$item;
        }
        return $this->json($final, 200, [], ["groups"=>"conv:read"]);
    }



    #[Route('/getspecificconv/{id}', name: 'private_conversation_get_specific_conv', methods: 'GET')]
    public function getSpecificConv(Profile $profile, PrivateConversationRepository $repository){

        $asCreator = $repository->findBy(["convCreator"=>$this->getUser()]);
        $asRecipient = $repository->findBy(["convRecipient"=>$this->getUser()]);

        $returnable = [];

        foreach ($asCreator as $item){
            if ($item->getConvRecipient() === $profile){
                $returnable[]=$item;
            }
        }
        foreach ($asRecipient as $item){
            if ($item->getConvCreator() === $profile){
                $returnable[]=$item;
            }
        }
        return $this->json($returnable, 200, [], ["groups"=>"conv:read"]);
    }

    #[Route('/getwithid/{id}', name: 'private_conversation_get_with_id', methods: 'GET')]
    public function getConvWithId(PrivateConversation $conversation){
        return $this->json($conversation, 200, [], ["groups"=>"conv:read", "message:read"]);
    }
}