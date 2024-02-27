<?php

namespace App\Controller;

use App\Entity\GroupConversation;
use App\Entity\Profile;
use App\Services\GroupConversationServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

class GroupeConversationController extends AbstractController
{
    #[Route('/api/creategroupconversation', name: 'group_converstation_create', methods: 'POST')]
    public function create(EntityManagerInterface $manager){
        $groupConversation = new GroupConversation();
        $groupConversation->addConvCreator($this->getUser()->getProfile());
        $manager->persist($groupConversation);
        $manager->flush();
        return $this->json($groupConversation->getId(), 201);
    }

    #[Route('/api/addtogroup/{profileId}/{groupId}', name: 'group_converstation_add', methods: 'POST')]
    public function addToGroup(GroupConversationServices $services, EntityManagerInterface $manager,
    #[MapEntity(mapping: ['profileId'=>'id'])]Profile $profile,
    #[MapEntity(mapping: ['groupId'=>'id'])]GroupConversation $groupConversation,
    ){
        if ($services->isInConv($profile, $groupConversation)){return $this->json("User already in conversation");}
        $groupConversation->addConvRecipient($profile);
        $manager->persist($groupConversation);
        $manager->flush();
        return $this->json("Added user to conv", 200);
    }
}
