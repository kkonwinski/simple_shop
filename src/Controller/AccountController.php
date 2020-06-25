<?php

namespace App\Controller;

use App\Entity\UserInfo;
use App\Form\UserInfoType;
use App\Repository\UserInfoRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AccountController extends AbstractController
{

    private $security;
    private $userRepository;
    private $em;

    private $userInfoRepository;

    public function __construct(Security $security, EntityManagerInterface $em, UserRepository $userRepository, UserInfoRepository $userInfoRepository)
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->userInfoRepository = $userInfoRepository;
    }

    /**
     * @Route("/account", name="account")
     */
    public function index(Request $request)
    {
        $users = $this->userRepository->find($this->security->getUser());
        $getUser = $this->userInfoRepository->findOneBy(['user' => $users->getId()]);
        $form = $this->createForm(UserInfoType::class, $getUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userInfo = $form->getData();
            if (!$getUser) {
                $userInfo->setUser($this->security->getUser());
            }
            $this->em->persist($userInfo);

            $this->em->flush();

            return $this->redirectToRoute('account');
        }

        return $this->render('account/index.html.twig', [
            'users' => $users,
            'userInfo' => $form->createView()
        ]);
    }
}
