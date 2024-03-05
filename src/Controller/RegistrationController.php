<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Services\UserServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $manager , SerializerInterface $serializer, UserServices $service): Response
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        if (!$service->isValid($user->getEmail())){
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                )
            );
            $profile = new Profile();
            $profile->setUsername(strtok($user->getEmail(), "@"));
            $profile->setName("No name for now");
            $profile->setLastname("No name for now");
            $user->setProfile($profile);
            $currentDate = new \DateTimeImmutable();
            $profile->setCreatedAt($currentDate);
            $manager->persist($user);
            $manager->persist($profile);
            $manager->flush();
            return $this->json($user, 200,[], ['groups'=>'user:read']);
        }
        return $this->json('User already exist !', 400);
    }
}
