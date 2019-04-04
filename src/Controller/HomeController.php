<?php

// src/Controller/HelloController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Page d'accueil.
     *
     * @Route("/", name="accueil")
     */
    public function home()
    {
        return $this->render('home.html.twig', []);
    }
}
