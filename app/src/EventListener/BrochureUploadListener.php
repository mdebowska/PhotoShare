<?php
namespace App\EventListener;

use App\Service\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BrochureUploadListener
{
    /**
     * @var FileUploader
     */
    private $uploader;

    /**
     * BrochureUploadListener constructor.
     * @param FileUploader $uploader
     */
    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * @param $entity
     * @throws \Exception
     */
    private function uploadFile($entity)
    {
// upload only works for Product entities
        if (!$entity instanceof Product) {
            return;
        }

        $file = $entity->getBrochure();

// only upload new files
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
            $entity->setBrochure($fileName);
        } elseif ($file instanceof File) {
// prevents the full file path being saved on updates
// as the path is set on the postLoad listener
            $entity->setBrochure($file->getFilename());
        }
    }
}