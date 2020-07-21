<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\GetAllegroApiToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/allegro")
 */
class AllegroController extends AbstractController
{
    /**
     * @Route("/", name="allegro")
     */
    public function index()
    {
        //TEST
        $allegroApiToken = $this->getUser()->getAccessToken();
        return $this->render('allegro/index.html.twig', [
            'allegroToken' => $allegroApiToken
        ]);
    }


}
