<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ShoppingCart;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="product_index")
     */
    public function index(ProductRepository $product)
    {

        $products = $product->findProductByDate();
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


        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();
            $this->em->persist($product);
            $this->em->flush();
            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'productForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit_product")
     */
    public function edit(Product $product, Request $request)
    {

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();
            $this->em->persist($product);
            $this->em->flush();

            return $this->redirectToRoute('edit_product', [
                'id' => $product->getId(),
            ]);

        }
        return $this->render('product/edit.html.twig', [
            'productForm' => $form->createView(),
            'productId' => $product->getId()
        ]);
    }


}
