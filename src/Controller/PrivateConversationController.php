<?php

namespace App\Controller;

use App\Entity\PrivateConversation;
use App\Entity\PrivateMessage;
use App\Repository\PrivateConversationRepository;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PrivateConversationController extends AbstractController
{
    #[Route('/conversation', name: 'app_conversation')]
    public function index(): Response
    {
        return $this->render('conversation/index.html.twig', [
            'controller_name' => 'PrivateConversationController',
        ]);
    }


    #[Route('/api/createprivateconversation/{id}', name: 'private_converstation_create')]
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


}
