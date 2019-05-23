<?php
/**
 * Photo controller.
 */

namespace App\Controller;

use App\Entity\Photo;
//use App\Form\PhotoType;
use App\Repository\PhotoRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class PhotoController.
 *
 * @Route("/photo")
 */
class PhotoController extends AbstractController
{
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

        //dump($pagination);
        return $this->render(
            'photo/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * View action.
     *
     * @param \App\Entity\Photo $photo Photo entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="photo_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(Photo $photo): Response
    {
        dump($photo);
        return $this->render(
            'photo/view.html.twig',
            ['photo' => $photo]
        );
    }
//    /**
//     * New action.
//     *
//     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
//     * @param \App\Repository\PhotoRepository        $repository Photo repository
//     * @param \Symfony\Component\Security\Core\Encoder\PhotoPasswordEncoderInterface $passwordEncoder
//     *
//     * @return \Symfony\Component\HttpFoundation\Response HTTP response
//     *
//     * @throws \Doctrine\ORM\ORMException
//     * @throws \Doctrine\ORM\OptimisticLockException
//     *
//     * @Route(
//     *     "/new",
//     *     methods={"GET", "POST"},
//     *     name="photo_new",
//     * )
//     */
//    public function new(Request $request, PhotoRepository $repository, PhotoPasswordEncoderInterface $passwordEncoder): Response
//    {
//        $photo = new Photo();
//        $form = $this->createForm(PhotoType::class, $photo);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            $photo->setRoles(['ROLE_USER']);
////        $photo->setPassword($this->passwordEncoder->encodePassword(
////            $photo,
////            $photo['password']
////        ));
//            $photo->setPassword($passwordEncoder->encodePassword(
//                $photo,
//                'photo1234'
//            ));
//
//            $repository->save($photo);
//
//            $this->addFlash('success', 'message.created_successfully');
//
//            return $this->redirectToRoute('photo_index');
//        }
//
//        return $this->render(
//            'photo/new.html.twig',
//            ['form' => $form->createView()]
//        );
//    }
//    /**
//     * Edit action.
//     *
//     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
//     * @param \App\Entity\Photo                      $photo   Photo entity
//     * @param \App\Repository\PhotoRepository        $repository Photo repository
//     * @param \Symfony\Component\Security\Core\Encoder\PhotoPasswordEncoderInterface $passwordEncoder
//     *
//     * @return \Symfony\Component\HttpFoundation\Response HTTP response
//     *
//     * @throws \Doctrine\ORM\ORMException
//     * @throws \Doctrine\ORM\OptimisticLockException
//     *
//     * @Route(
//     *     "/{id}/edit",
//     *     methods={"GET", "PUT"},
//     *     requirements={"id": "[1-9]\d*"},
//     *     name="photo_edit",
//     * )
//     */
//    public function edit(Request $request, Photo $photo, PhotoRepository $repository, PhotoPasswordEncoderInterface $passwordEncoder): Response
//    {
//        $form = $this->createForm(PhotoType::class, $photo, ['method' => 'put']);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            $photo->setPassword($passwordEncoder->encodePassword(
//                $photo,
//                $photo->getPassword()
//            ));
//            $repository->save($photo);
//
//            $this->addFlash('success', 'message.updated_successfully');
//
//            return $this->redirectToRoute('photo_index');
//        }
//
//        return $this->render(
//            'photo/edit.html.twig',
//            [
//                'form' => $form->createView(),
//                'photo' => $photo,
//            ]
//        );
//    }
//    /**
//     * Delete action.
//     *
//     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
//     * @param \App\Entity\Photo                      $photo   Photo entity
//     * @param \App\Repository\PhotoRepository        $repository Photo repository
//     *
//     * @return \Symfony\Component\HttpFoundation\Response HTTP response
//     *
//     * @throws \Doctrine\ORM\ORMException
//     * @throws \Doctrine\ORM\OptimisticLockException
//     *
//     * @Route(
//     *     "/{id}/delete",
//     *     methods={"GET", "DELETE"},
//     *     requirements={"id": "[1-9]\d*"},
//     *     name="photo_delete",
//     * )
//     */
//    public function delete(Request $request, Photo $photo, PhotoRepository $repository): Response
//    {
////        if ($photo->getTasks()->count()) {
////            $this->addFlash('warning', 'message.photo_contains_tasks');
////
////            return $this->redirectToRoute('photo_index');
////        }
//
//        $form = $this->createForm(FormType::class, $photo, ['method' => 'DELETE']);
//        $form->handleRequest($request);
//
//        if ($request->isMethod('DELETE')) {
////            $form->submit($request->request->get($form->getName()));
//            $repository->delete($photo);
//
//            $this->addFlash('success', 'message.deleted_successfully');
//
//            return $this->redirectToRoute('photo_index');
//        }
//
//        return $this->render(
//            'photo/delete.html.twig',
//            [
//                'form' => $form->createView(),
//                'photo' => $photo,
//            ]
//        );
//    }
}