<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/cart")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class CartController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="cart")
     */
    public function index(SessionInterface $session, ProductRepository $productRepository)
    {
        // $session->remove('cart');
        $cart = $session->get('cart', []);

        $cartWithData = [];

        foreach ($cart as $id => $quantity) {


            $cartWithData[] = [

                'product' => $productRepository->find($id),
                'quantity' => $quantity,
                'user' => $cart['user']
            ];
        }
        $total = 0;
        foreach ($cartWithData as $item) {

            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }

        return $this->render('cart/index.html.twig', [
            'items' => $cartWithData,
            'total' => $total
        ]);
    }


    /**
     * @Route("/add/{id}", name="cart_add",requirements={"id"="\d+"})
     */
    public function add($id, SessionInterface $session, ProductRepository $productRepository)
    {
        $productQuantity = $productRepository->find($id);

        $cart = $session->get('cart', []);
        $cart['user'] = $this->security->getUser();
        if (!empty($cart[$id])) {
            if ($cart[$id] != $productQuantity->getQuantity()) {
                $cart[$id]++;
            }
        } else {
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);
        return $this->redirectToRoute('product_index');
    }

    /**
     * @Route("/remove/{id}", name="cart_remove",requirements={"id"="\d+"})
     */
    public function remove($id, Session $session)
    {
        $cart = $session->get('cart', []);

        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        $session->set('cart', $cart);
        return $this->redirectToRoute('cart');

    }
}
