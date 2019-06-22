<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\User;
use App\Entity\Userdata;
use App\Form\EmailType;
use App\Form\Password2Type;
use App\Form\UserdataType;
use App\Form\UserType;
use App\Repository\PhotoRepository;
use App\Repository\UserdataRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController.
 *
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\UserRepository        $repository User repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(
     *     "/",
     *     name="user_index",
     * )
     */
    public function index(Request $request, UserRepository $repository, PaginatorInterface $paginator): Response
    {

        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            User::NUMBER_OF_ITEMS
        );

        return $this->render(
            'user/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * View action.
     *
     * @param \App\Entity\User $user User entity
     * @param Request $request
     * @param PhotoRepository $repository
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="user_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(User $user, Request $request, PhotoRepository $repository, PaginatorInterface $paginator): Response
    {
        $photo_pagination = $paginator->paginate(
            $repository->findByUser($user->getId()),
            $request->query->getInt('page', 1),
            Photo::NUMBER_OF_ITEMS
        );

        return $this->render(
            'user/view.html.twig',
            [
                'user' => $user,
                'pagination' => $photo_pagination]
        );
    }
    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\UserRepository        $repository User repository
     * @param \App\Repository\UserdataRepository      $userdataRepository Userdata repository
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="user_new",
     * )
     */
    public function new(Request $request, UserRepository $repository, UserdataRepository $userdataRepository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['method' => 'post']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setRoles(['ROLE_USER']);
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $user->getPassword()
            ));

            $repository->save($user);

            $userdata = new Userdata();
            $userdata->setUser($user);
            $userdataRepository->save($userdata);

            $this->addFlash('success', 'message.account_successfully_added');

            return $this->redirectToRoute('home_index');
        }

        return $this->render(
            'user/new.html.twig',
            ['form' => $form->createView()]
        );
    }
    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\User                      $user   User entity
     * @param \App\Repository\UserdataRepository        $repository_data Userdata repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_edit",
     * )
     */
    public function edit(Request $request, User $user, UserdataRepository $repository_data): Response
    {
        /* 3 formularze */

        if ($user != $this->getUser() and $this->isGranted('ROLE_ADMIN') == false) {
            $this->addFlash('warning', 'message.it_is_not_your_profile');

            return $this->redirectToRoute('home_index');
        }

        $userdata = $repository_data->findOneByUser($user->getId());

        $form_email = $this->createForm(EmailType::class, $user, ['method' => 'put']);
        $form_email->handleRequest($request);

        $form_password = $this->createForm(Password2Type::class, $user, ['method' => 'put']);
        $form_password->handleRequest($request);

        $form_data = $this->createForm(UserdataType::class, $userdata, ['method' => 'put']);
        $form_data->handleRequest($request);

        $form_role = $this->createForm(FormType::class, $user, ['method' => 'PUT']);
        $form_role->handleRequest($request);

        return $this->render(
            'user/edit.html.twig',
            [
                'form_email' => $form_email->createView(),
                'form_password' => $form_password->createView(),
                'form_data' => $form_data->createView(),
                'form_role' => $form_role->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * Edit mail action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\UserRepository        $repository User repository
     * @param \App\Entity\User                      $user   User entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit_mail",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_edit_mail",
     * )
     */
    public function edit_mail(Request $request, User $user, UserRepository $repository): Response
    {
        $form_email = $this->createForm(EmailType::class, $user, ['method' => 'put']);
        $form_email->handleRequest($request);
        if ($form_email->isSubmitted() && $form_email->isValid()) {
            $repository->save($user);
            $this->addFlash('success', 'message.updated_successfully');
            return $this->redirectToRoute('user_view', ['id' => $user->getId()], 301);
        }else{
            return $this->redirectToRoute('user_edit', ['id' => $user->getId()], 301);
        }
    }

    /**
     * Edit password action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\User                      $user   User entity
     * @param \App\Repository\UserRepository        $repository User repository
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit_password",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_edit_password",
     * )
     */
    public function edit_password(Request $request, User $user, UserRepository $repository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form_password = $this->createForm(Password2Type::class, $user, ['method' => 'put']);
        $form_password->handleRequest($request);
        if ($form_password->isSubmitted() && $form_password->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $user->getPassword()
            ));
            $repository->save($user);
            $this->addFlash('success', 'message.updated_successfully');
            return $this->redirectToRoute('user_view', ['id' => $user->getId()], 301);
        }else{
            return $this->redirectToRoute('user_edit', ['id' => $user->getId()], 301);
        }
    }

    /**
     * Edit data action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\UserdataRepository        $repository Userdata repository
     * @param \App\Entity\Userdata                      $userdata   Userdata entity
     * @param \App\Entity\User                      $user   User entity
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit_data",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_edit_data",
     * )
     */
    public function edit_data(Request $request, Userdata $userdata, User $user, UserdataRepository $repository): Response
    {
        $form_data = $this->createForm(UserdataType::class, $userdata, ['method' => 'put']);
        $form_data->handleRequest($request);
        if ($form_data->isSubmitted() && $form_data->isValid()) {

            $repository->save($userdata);
            $this->addFlash('success', 'message.updated_successfully');
            return $this->redirectToRoute('user_view', ['id' => $user->getId()], 301);
        }else{
            return $this->redirectToRoute('user_edit', ['id' => $user->getId()], 301);
        }
    }
/**
     * Edit roles action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\User                      $user   User entity
     * @param \App\Repository\UserRepository        $repository User repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit_roles",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_edit_roles",
     * )
     */
    public function edit_roles(Request $request, User $user, UserRepository $repository): Response
    {
        $user_role = $user->getRoles();
        $form_role = $this->createForm(FormType::class, $user, ['method' => 'PUT']);
        $form_role->handleRequest($request);

        if ($this->isGranted('ROLE_ADMIN')) {
            if ($form_role->isSubmitted() && $form_role->isValid()) {
                if ($user_role == ['ROLE_USER']) {
                    $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
                } else {
                    $user->setRoles(['ROLE_USER']);
                }
                $repository->save($user);

                $this->addFlash('success', 'message.updated_successfully');
                return $this->redirectToRoute('user_view', ['id' => $user->getId()], 301);
            }
        }
        return $this->redirectToRoute('user_edit', ['id' => $user->getId()], 301);
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\User                      $user   User entity
     * @param \App\Repository\UserRepository        $repository User repository
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
     *     name="user_delete",
     * )
     */
    public function delete(Request $request, User $user, UserRepository $repository): Response
    {
        if ($user != $this->getUser() and $this->isGranted('ROLE_ADMIN') == false) {
            $this->addFlash('warning', 'message.it_is_not_your_profile');

            return $this->redirectToRoute('home_index');
        }

        $form = $this->createForm(FormType::class, $user, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE')) {
            $repository->delete($user);

            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('home_index');
        }

        return $this->render(
            'user/delete.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}