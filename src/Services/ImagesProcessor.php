<?php

namespace App\Services;

use App\Entity\PrivateConversation;
use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use phpDocumentor\Reflection\Types\Collection;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImagesProcessor
{
    private ImageRepository $imageRepository;
    private UploaderHelper $uploaderHelper;
    private CacheManager $cacheManager;

    public function __construct(ImageRepository $imageRepository, UploaderHelper $uploaderHelper, CacheManager $cacheManager){
        $this->imageRepository = $imageRepository;
        $this->uploaderHelper = $uploaderHelper;
        $this->cacheManager = $cacheManager;
    }


    public function getImagesFromImageIds(array $imageIds) :array{
        $images = [];
        foreach ($imageIds as $imageId){
            $image = $this->imageRepository->find($imageId);
            if ($image){
                $images[] = $image;
            }
        }
        return $images;
    }


    public function setImagesUrlsOfMessagesFromPrivateConversation(PrivateConversation $conversation){
        $messages = $conversation->getMessages();
        foreach ($messages as $message){
            $images = $message->getImages();

            $imagesUrls = new ArrayCollection();
            foreach ($images as $image){
                $imageUrl = [];
                $imageUrl["id"] = $image->getId();
                $imageUrl["url"] = $this->cacheManager->generateUrl($this->uploaderHelper->asset($image), 'vignette');
                $imagesUrls[] = $imageUrl;

            }
            $message->setImagesUrls($imagesUrls);

        }
        return $messages;
    }
}