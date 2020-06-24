<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/order")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class OrderController extends AbstractController
{

    private $security;
    private $em;
    private $session;

    public function __construct(Security $security, EntityManagerInterface $em, SessionInterface $session)
    {
        $this->security = $security;
        $this->em = $em;
        $this->session = $session;
    }

    /**
     * @Route("/new", name="new_order")
     */
    public function new(ProductRepository $productRepository)
    {
        $order = new Order();

        $sumQuantity = 0;
        $totalPrice = 0;
        foreach ($this->session->get('cart') as $pid => $quantity) {
            $sumQuantity += $quantity;
            $productCart = $productRepository->find($pid);
            if ($productCart->getQuantity() != 0 || $productCart->getQuantity() > $quantity) {
                $order->addProduct($productCart);
                $totalPrice += $productCart->getPrice() * $quantity;
                $productCart->setQuantity($productCart->getQuantity() - $quantity);
            }
        }

        $order->setTotalPrice($totalPrice);
        $order->setQuantity($sumQuantity);
        $order->setUser($this->security->getUser());
        $this->em->persist($order);
        $this->em->flush();


        //  $this->session->remove('cart');
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }
}
