<?php

namespace App\Controller;

use App\Entity\ShoppingCart;
use App\Repository\ShoppingCartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ShoppingCartController extends AbstractController
{
    /**
     * @Route("/shopping/cart", name="shopping_cart")
     */
    public function index(ShoppingCartRepository $shoppingCart)
    {
        $r = $shoppingCart->getProductName();
        dd($r);
//        return $this->render('shopping_cart/index.html.twig', [
//            'shoppingCart' => $shoppingCart->findAll(),
//        ]);
    }
}
