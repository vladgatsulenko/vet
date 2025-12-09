<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: [Request::METHOD_GET])]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
}
