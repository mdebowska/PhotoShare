<?php
/**
 * Hello World controller.
 */

namespace App\Controller;

use App\Entity\Photo;
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
 * Class HelloWorldController.
 */
class HomeController extends AbstractController
{
    /**
     * Index action.
     *
     * @param string $name User input
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="home_index",
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
            'home/index.html.twig',
            ['pagination' => $pagination]
        );

    }


    /**
     * Search action.
     *
     * @param \App\Repository\TagRepository        $repository Tag repository
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/search",
     *     name="search_index",
     * )
     */
    public function search(Request $request, TagRepository $repository): Response
    {
        $tags = $repository->findAll();

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

//        if ($form->isSubmitted() && $form->isValid()) {
//
//            return $this->redirectToRoute('photo_tag', ['id' => $tag->getName()], 301);
//        }


        return $this->render(
            'home/search.html.twig',
            [
                'tags' => $tags,
                'form' => $form->createView()
            ]
        );
    }
}
