<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
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
        return $this->render('home/homePage.html.twig', [
            'controller_name' => 'HomeController',
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
