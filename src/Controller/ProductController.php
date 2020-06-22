<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     *
     * @param ProductRepository $product
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ProductRepository $product, PaginatorInterface $paginator, Request $request)
    {

        $products = $product->findProductByDate();
        return $this->render('product/index.html.twig', [
            'products' => $paginator->paginate($products, $request->query->getInt('page', 1), 4)
        ]);
    }

    /**
     * @Route("/new",name="product_new")
     * @param Request $request
     * @param ImageUploader $imageUploader
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function new(Request $request, ImageUploader $imageUploader)
    {
        $form = $this->createForm(ProductFormType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();
            /** @var ImageUploader $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $imageName = $imageUploader->upload($imageFile);
                $product->setImage($imageName);

            }
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
     * @param Product $product
     * @param Request $request
     * @param ImageUploader $imageUploader
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @var ImageUploader $imageFile
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function edit(Product $product, Request $request, ImageUploader $imageUploader)
    {

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();
            /** @var ImageUploader $imageFile */
            $imageFile = $form['image']->getData();

            if ($imageFile) {
                $imageName = $imageUploader->upload($imageFile);
                $product->setImage($imageName);

            }

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

    /**
     * @Route("/show/{id}", name="show_product")
     */
    public function show(Product $product)
    {

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }


}
