<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
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

    #[Route('/admin/create/product', name: 'app_create_product')]
    public function createProductAction(Request $request): Response
    {
        // tao moi entity
        $product = new Product();
        // tao form theo Create Product Form
        $form = $this->createForm(ProductType::class, $product);
        // xu ly yeu cau tu request
        $form->handleRequest($request);
        // kiem tra cai form da duoc gui hay chua va kiem loi?
        if ($form->isSubmitted() && $form->isValid()) {
            $newProduct = $form->getData();
            $imgPath = $form->get('image')->getData();
            if ($imgPath) {
                $newFileName = uniqid() . '.' . $imgPath->guessExtension();
                try {
                    $imgPath->move($this->getParameter('kernel.project_dir') . '/public/uploads', $newFileName);
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $newProduct->setImage('/uploads/' . $newFileName);
            }
            $this->em->persist($newProduct);
            $this->em->flush();

            return $this->redirectToRoute('app_admin_list_products');
        }
        return $this->render('admin/createProduct.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/admin/list/product', name: 'app_admin_list_products', methods: ['GET'])]
    public function getManyProducts(): Response
    {
        $getProducts = $this->productRepository->findAll();
        return $this->render('admin/listProducts.html.twig', ['list_products' => $getProducts]);
    }

    #[Route('/admin/details/product/{id}', name: 'app_admin_details_product', methods: ['GET'])]
    public function getOneProducts($id): Response
    {
        $getDetails = $this->productRepository->find($id);
        $getCateId = $this -> categoryRepository -> find($getDetails -> getCategoryId());
        $getDetailsCate = $this -> categoryRepository -> find($getCateId);
        return $this->render('admin/detailsProduct.html.twig', ['details_products' => $getDetails,'category_details' => $getDetailsCate]);
    }

    #[Route('/admin/edit/product/{id}', name: 'app_admin_edit_product', methods:['GET','POST'])]
    public function updateProduct(Request $request, $id): Response
    {
        $data = $this->productRepository->find($id);
        $form = $this->createForm(ProductType::class, $data);
        $form->handleRequest($request);
        $imgPath = $form->get('image')->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            if ($imgPath) {
                if ($data->getImage() !== null) {
                    if (!file_exists($this->getParameter('kernel.project_dir') . $data->getImage())) {
                        $this->getParameter('kernel.project_dir') . $data->getImage();
                        $newFileName = uniqid() . '.' . $imgPath->guessExtension();
                        try {
                            $imgPath->move($this->getParameter('kernel.project_dir') . '/public/uploads', $newFileName);
                        } catch (FileException $e) {
                            return new Response($e->getMessage());
                        }
                        $data->setImage('/uploads/' . $newFileName);
                        $this->em->flush();
                        return $this->redirectToRoute('app_admin_list_products');
                    }
                }
            } else {
                $data->setProductName($form->get('productName')->getData());
                $data->setCategoryId($form->get('CategoryId')->getData());
                $data->setPrice($form->get('price')->getData());
                $data->setQuantity($form->get('quantity')->getData());
                $data->setBrand($form->get('brand')->getData());
                $data->setDescription($form->get('description')->getData());
                $this->em->flush();
                return $this->redirectToRoute('app_admin_list_products');
            }
        }
        return $this->render('admin/editProduct.html.twig', ['form' => $form->createView(), 'data' => $data]);
    }

    #[Route('/admin/delete/product/{id}', name: 'app_admin_delete_product', methods: ['GET', 'POST'])]
    public function deleteOneProduct($id): Response
    {
        $product = $this->productRepository->find($id);
        $this->em->remove($product);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_list_products');
    }

    #[Route('/admin/create/category', name: 'admin_create_category', methods:['GET','POST'])]
    public function createCategoryAction(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $newCat = $form->getData();
            $this -> em -> persist($newCat);
            $this -> em -> flush();
            return $this -> redirectToRoute('app_admin_list_categories');
        }

        return $this->render('admin/createCategory.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/list/category', name: 'app_admin_list_categories', methods: ['GET'])]
    public function getManyCategories(): Response
    {
        $getCategories = $this->categoryRepository->findAll();
        return $this->render('admin/listCategories.html.twig', ['list_categories' => $getCategories]);
    }

    #[Route('/admin/update/category/{id}', name: 'app_admin_edit_category')]    
    public function updateCategoryAction($id, Request $request){
        $category = $this -> categoryRepository -> find($id);
        $form = $this -> createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $category -> setName($form->get('name')->getData());
            $this -> em -> flush();
            return $this -> redirectToRoute('app_admin_list_categories');
        }
        return $this -> render('admin/updateCategory.html.twig',['form' => $form->createView(),'category'=>$category]);
    }

    #[Route('/admin/delete/category/{id}', name: 'app_admin_delete_category', methods:['GET','DELETE'])] 
    public function deleteCategoryAction($id):Response{
        $cate = $this->categoryRepository -> find($id);
        $this -> em -> remove($cate);
        $this -> em -> flush();
        return $this -> redirectToRoute('app_admin_list_categories');
    }

    #[Route('/admin/list/category/{id}',name:'app_admin_details_category', methods:['GET'])]
    public function getOneCategory($id):Response {
        return $this -> render('admin/detailsCategory.html.twig',['detials_category'=>$this->categoryRepository->find($id)]);
    }
}
