<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\CreateProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductDetailRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
 
    private $categoryRepository;
    private $productRepository;
    private $productDetailsRepository;
    private $em;
    public function __construct(EntityManagerInterface $em,CategoryRepository $categoryRepository, ProductRepository $productRepository,ProductDetailRepository $productDetailsRepository ){
        
        $this -> categoryRepository = $categoryRepository;
        $this -> productRepository = $productRepository;
        $this -> productDetailsRepository = $productDetailsRepository;
        $this -> em = $em;
    }

    // Ham lay tat ca danh sach san pham
    #[Route('/admin/list/product', name: 'list_product')]
    public function getAllProductsAction(): Response
    {
        $getProduct = $this -> productRepository -> findAll();
        return $this -> render('admin/listProduct.html.twig',['list_products' => $getProduct]);
    }
    // ham tao san pham
    #[Route('/admin/create/product', name: 'create_product')]
    public function createProductAction(Request $request): Response
    {
        // tao moi entity
       $product = new Product();
       // tao form theo Create Product Form
       $form = $this->createForm(CreateProductType::class, $product);
       // xu ly yeu cau tu request
       $form->handleRequest($request);

       // kiem tra cai form da duoc gui hay chua va kiem loi?
       if($form->isSubmitted() && $form->isValid()){
           $getData = $form -> getData();
           $this -> em -> persist($getData);
        $this -> em -> flush();

       }
        return $this -> render('admin/createProduct.html.twig',['form' => $form -> createView()]);
    }
}
