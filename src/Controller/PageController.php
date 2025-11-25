<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_main', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('page/index.html.twig');
    }

    #[Route('/about', name: 'app_about', methods: ['GET'])]
    public function about(): Response
    {
        return $this->render('page/about.html.twig');
    }

    #[Route('/contact', name: 'app_contact', methods: ['GET','POST'])]
    public function contact(): Response
    {
        return $this->render('page/contact.html.twig');
    }
}
