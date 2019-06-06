<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\Tag;
use App\Form\SearchType;
use App\Repository\PhotoRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController.
 *
 * @Route("/search")
 */
class SearchController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \App\Repository\TagRepository        $repository Tag repository
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="search_index",
     * )
     */
    public function index(Request $request, TagRepository $repository): Response
    {
        $tags = $repository->findAll();

        $value = $request->get('search'); //wartość formularza

        if($value){
            return $this->redirectToRoute('search_view', ['value' => $value], 301);
        }

        return $this->render(
            'search/index.html.twig',
            [
                'tags' => $tags
            ]
        );
    }

    /**
     * View action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     *
     * @param \App\Repository\PhotoRepository        $photoRepository Photo repository
     * @param \App\Repository\PhotoRepository        $userRepository User repository
     * @param $value
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{value}",
     *     name="search_view",
     * )
     */
    public function view(Request $request, PhotoRepository $photoRepository, UserRepository $userRepository, $value): Response
    {
        $photos = $photoRepository->findBySearchValue($value)->getQuery()->getResult();
        dump($photos);
        $users = $userRepository->findBySearchValue($value)->getQuery()->getResult();
//        dump($users);


        return $this->render(
            'search/view.html.twig',
            [
                'photos' => $photos,
                'users' => $users
            ]
        );
    }


}
