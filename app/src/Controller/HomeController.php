<?php
/**
 * Hello World controller.
 */

namespace App\Controller;

use App\Entity\Photo;
use App\Repository\PhotoRepository;
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
}