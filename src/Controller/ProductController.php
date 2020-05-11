<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index")
     */
    public function index(ProductRepository $product)
    {
        $products = $product->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/new",name="product_new")
     */
    public function new(Request $request)
    {
        $form = $this->createForm(ProductFormType::class);
        $form->handleRequest($request);


        return $this->render('product/new.html.twig', [
            'productForm' => $form->createView()
        ]);
    }
}
