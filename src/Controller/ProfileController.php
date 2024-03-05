<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Repository\ProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route ("/api/profile")]
class ProfileController extends AbstractController
{
    #[Route('/whoami', name: 'app_profile_whoami')]
    public function index(): Response
    {
        return $this->json($this->getUser()->getProfile(), 200, [], ["groups"=>"user:read"]);
    }


    #[Route('/getpeople', name: 'app_profile_get_people')]
    public function getPeople(ProfileRepository $repository){
        $returnable = [];

        return $this->json($repository->findAll(), 200, [], ["groups"=>"user:read"]);
    }


    #[Route('/whoishe/{id}', name:'who_is_he')]
    public function whoIsHe(Profile $profile)
    {
        return $this->json($profile, 200, [], ["groups"=>"user:read"]);
    }
}
