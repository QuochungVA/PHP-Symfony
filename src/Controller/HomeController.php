<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    private $em;
    private $categoryRepository;
    private $productRepository;

    public function __construct(CategoryRepository $categoryRepository, ProductRepository $productRepository, EntityManagerInterface $em)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->em = $em;
    }


    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/homePage.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function aboutAction(): Response
    {
        return $this->render('home/homePage.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/list/product', name: 'app_list_product')]
    public function productAction(): Response
    {
        $product = $this->productRepository->findAll();
        return $this->render('home/listProduct.html.twig', [
            'product_list' => $product,
        ]);
    }

    #[Route('/details/product/{id}', name: 'app_details_product')]
    public function detailsAction($id): Response
    {
        $getOne = $this -> productRepository -> find($id);
        return $this->render('home/detailProduct.html.twig', [
            'details' => $getOne,
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contactAction(): Response
    {
        return $this->render('home/homePage.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
