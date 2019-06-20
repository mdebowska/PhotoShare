<?php
/**
 * Comment controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController.
 *
 * @Route("/comment")
 */
class CommentController extends AbstractController
{

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Comment                      $comment   Comment entity
     * @param \App\Repository\CommentRepository        $repository Comment repository
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
     *     name="comment_edit",
     * )
     */
    public function edit(Request $request, Comment $comment, CommentRepository $repository): Response
    {
        if ($comment->getUser() != $this->getUser() and $this->isGranted('ROLE_ADMIN') == false) {
            $this->addFlash('warning', 'message.it_is_not_your_comment');

            return $this->redirectToRoute('home_index');
        }
        $form = $this->createForm(CommentType::class, $comment, ['method' => 'put']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $repository->save($comment);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('photo_view', ['id' => $comment->getPhoto()->getId()], 301);
        }

        return $this->render(
            'comment/edit.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
            ]
        );
    }
    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Comment                      $comment   Comment entity
     * @param \App\Repository\CommentRepository        $repository Comment repository
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
     *     name="comment_delete",
     * )
     */
    public function delete(Request $request, Comment $comment, CommentRepository $repository): Response
    {
        if ($comment->getUser() != $this->getUser() and $this->isGranted('ROLE_ADMIN') == false) {
            $this->addFlash('warning', 'message.it_is_not_your_comment');

            return $this->redirectToRoute('home_index');
        }
        $photo_id = $comment->getPhoto()->getId();

        $form = $this->createForm(FormType::class, $comment, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE')) {
            $repository->delete($comment);

            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('photo_view', ['id' => $photo_id], 301);
        }

        return $this->render(
            'comment/delete.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
            ]
        );
    }
}