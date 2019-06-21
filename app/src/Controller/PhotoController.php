<?php
/**
 * Photo controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Likerate;
use App\Entity\Photo;
use App\Entity\Tag;
use App\Form\CommentType;
use App\Form\PhotoType;
use App\Repository\CommentRepository;
use App\Repository\FileRepository;
use App\Repository\LikerateRepository;
use App\Repository\PhotoRepository;
use App\Service\FileUploader;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PhotoController.
 *
 * @Route("/photo")
 */
class PhotoController extends AbstractController
{
    private $uploaderService = null;

    /**
     * PhotoController constructor.
     * @param FileUploader $uploaderService
     */
    public function __construct(FileUploader $uploaderService)
    {
        $this->uploaderService = $uploaderService;
    }

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\PhotoRepository        $repository Photo repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(
     *     "/",
     *     name="photo_index",
     * )
     */
    public function index(Request $request, PhotoRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Photo::NUMBER_OF_ITEMS
        );

        return $this->render(
            'photo/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * View action.
     *
     * @param Request $request
     * @param \App\Entity\Photo $photo Photo entity
     * @param LikerateRepository $likeRepository
     * @param CommentRepository $commentRepository
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}",
     *     name="photo_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(Request $request, Photo $photo, LikerateRepository $likeRepository, CommentRepository $commentRepository): Response
    {
        $like = new Likerate();
        $form_like = $this->createForm(FormType::class, $like);
        $form_like->handleRequest($request);
        if ($this->getUser()){
            $userHaveLiked = $likeRepository->CheckIfUserLikedPhoto($this->getUser()->getId(), $photo->getId());
        }else{
            $userHaveLiked = true; //dla anonima
        }
        if ($form_like->isSubmitted() && $form_like->isValid()) {
            $like->setPhoto($photo);
            $like->setUser($this->getUser());

            $likeRepository->save($like);

            return $this->redirectToRoute('photo_view', ['id' => $photo->getId()], 301);
        }

        $comment = new Comment();
        $form_comment = $this->createForm(CommentType::class, $comment);
        $form_comment->handleRequest($request);

        if ($form_comment->isSubmitted() && $form_comment->isValid()) {
            $comment->setPublicationDate(new \DateTime());
            $comment->setUser($this->getUser());
            $comment->setPhoto($photo);
            $commentRepository->save($comment);
            $this->addFlash('success', 'message.created_successfully');
        }

        return $this->render(
            'photo/view.html.twig',
            ['photo' => $photo,
                'likes' => $likeRepository->countByPhoto($photo->getId()),
                'form_like' => $form_like->createView(),
                'form_comment' => $form_comment->createView(),
                'userHaveLiked'=> $userHaveLiked
            ]
        );
    }

    /**
     * Tag action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\PhotoRepository        $repository Photo repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     * @param \App\Entity\Tag $tag Tag entity
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(
     *     "/tag/{id}",
     *     name="photo_tag",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function tag(Request $request, Tag $tag, PhotoRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->findByTag($tag),
            $request->query->getInt('page', 1),
            Photo::NUMBER_OF_ITEMS
        );

        return $this->render(
            'photo/tag.html.twig',
            ['pagination' => $pagination,
                'tag'=>$tag]
        );
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\PhotoRepository        $repository Photo repository
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="photo_new",
     * )
     */
    public function new(Request $request, PhotoRepository $repository, FileRepository $fileRepository): Response
    {
        /*1 podzielony formularz na 2*/

        $file = new \App\Entity\File();
        $form = $this->createForm(PhotoType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = new Photo();
            $photo->setPublicationDate(new \DateTime());
            $photo->setUser($this->getUser());

            $file->setSource($form->get('source')->getData());
            $fileRepository->save($file);

            $photo->setFile($file);
            $photo->setDescription($form->get('description')->getData());
            $photo->setCameraSpecification($form->get('camera_specification')->getData());
            $tags = $form->get('tags')->getData();
            foreach($tags as $tag){
                $photo->addTag($tag);
            }
            $repository->save($photo);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('user_view', ['id' => $this->getUser()->getId()], 301);

        }

        return $this->render(
            'photo/new.html.twig',
            ['form' => $form->createView()]
        );
    }
    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Photo                      $photo   Photo entity
     * @param \App\Repository\PhotoRepository        $repository Photo repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="photo_edit",
     * )
     */
    public function edit(Request $request, Photo $photo, PhotoRepository $repository): Response
    {

        /*1 podzielony formularz na 2*/

        if ($photo->getUser() != $this->getUser() and $this->isGranted('ROLE_ADMIN') == false) {
            $this->addFlash('warning', 'message.it_is_not_your_photo');

            return $this->redirectToRoute('home_index');
        }

        $form = $this->createForm(PhotoType::class, $photo, ['method' => 'put']);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $repository->save($photo);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('photo_view', ['id' => $photo->getId()], 301);
        }

        return $this->render(
            'photo/edit.html.twig',
            [
                'form' => $form->createView(),
                'photo' => $photo,
            ]
        );
    }
    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Photo                      $photo   Photo entity
     * @param \App\Repository\PhotoRepository        $repository Photo repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="photo_delete",
     * )
     */
    public function delete(Request $request, Photo $photo, PhotoRepository $repository): Response
    {

        if ($photo->getUser() != $this->getUser() and $this->isGranted('ROLE_ADMIN') == false) {
            $this->addFlash('warning', 'message.it_is_not_your_photo');

            return $this->redirectToRoute('home_index');
        }

        $form = $this->createForm(FormType::class, $photo, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE')) {
            $repository->delete($photo);

            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('user_view', ['id' => $this->getUser()->getId()], 301);
        }

        return $this->render(
            'photo/delete.html.twig',
            [
                'form' => $form->createView(),
                'photo' => $photo,
            ]
        );
    }
}