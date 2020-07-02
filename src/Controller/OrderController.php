<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Form\UserInfoType;
use App\Repository\ProductRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/order")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class OrderController extends AbstractController
{

    private $em;
    private $session;
    private $userRepository;
    private $userInfoRepository;

    public function __construct(EntityManagerInterface $em, SessionInterface $session, UserRepository $userRepository, UserInfoRepository $userInfoRepository)
    {

        $this->em = $em;
        $this->session = $session;
        $this->userRepository = $userRepository;
        $this->userInfoRepository = $userInfoRepository;
    }

    /**
     * @Route("/index",name="order_index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {

        $users = $this->userRepository->find($this->getUser());
        $getUser = $this->userInfoRepository->findOneBy(['user' => $users->getId()]);
        $form = $this->createForm(UserInfoType::class, $getUser);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userInfo = $form->getData();
            if (!$getUser) {
                $userInfo->setUser($this->getUser());
            }
            $this->em->persist($userInfo);
            $this->em->flush();

            return $this->redirectToRoute('new_order');
        }
        return $this->render('order/index.html.twig', [
            'userInfo' => $form->createView()
        ]);
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
        $order->setUser($this->getUser());
        $this->em->persist($order);
        $this->em->flush();

        $this->session->remove('cart');

        return $this->redirectToRoute('product_index');
    }
}
