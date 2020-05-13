<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/show/{id}", name="show_product")
     */
    public function show(Product $product){
//       $products= $product->find($product);

       return $this->render('product/show.html.twig',[
           'product'=>$product
       ]);
    }
}
