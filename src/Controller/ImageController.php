<?php

namespace App\Controller;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ImageController extends AbstractController
{
    #[Route('/api/image', name: 'app_image', methods: 'POST')]
    public function index(EntityManagerInterface $manager, Request $request)
    {
        $file = $request->files->get('image');
        $image = new Image();
        $image->setImageFile($file);
        $manager->persist($image);
        $manager->flush();
        return $this->json($image->getId(), 201);
    }
}
