<?php
/**
 * Photo upload listener.
 */

namespace App\EventListener;

use App\Entity\File as Photofile;
use App\Service\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class PhotoUploadListener.
 */
class PhotoUploadListener
{
    /**
     * Uploader service.
     *
     * @var \App\Service\FileUploader|null
     */
    protected $uploaderService = null;

    /**
     * PhotoUploadListener constructor.
     *
     * @param \App\Service\FileUploader $fileUploader File uploader service
     */
    public function __construct(FileUploader $fileUploader)
    {
        $this->uploaderService = $fileUploader;
    }

    /**
     * Pre persist.
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args Event args
     *
     * @throws \Exception
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * Pre update.
     *
     * @param \Doctrine\ORM\Event\PreUpdateEventArgs $args Event args
     *
     * @throws \Exception
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * Upload file.
     *
     * @param $entity entity
     *
     * @throws \Exception
     */
    private function uploadFile($entity): void
    {
        if (!$entity instanceof Photofile) {
            return;
        }

        $file = $entity->getSource();
        if ($file instanceof UploadedFile) {
            $filename = $this->uploaderService->upload($file);
            $entity->setSource($filename);
        }
    }

    /**
     * Post load.
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args Event args
     *
     * @throws \Exception
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof \App\Entity\File) {
            return;
        }

        if ($fileName = $entity->getSource()) {
            $entity->setSource(
                new File(
                    $this->uploaderService->getTargetDir().'/'.$fileName
                )
            );
        }
    }
}