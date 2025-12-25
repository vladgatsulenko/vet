<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfoController extends AbstractController
{
    #[Route('/about', name: 'app_about', methods: [Request::METHOD_GET])]
    public function about(): Response
    {
        return $this->render('info/about.html.twig');
    }

    #[Route('/contact', name: 'app_contact', methods: [Request::METHOD_GET])]
    public function contact(): Response
    {
        return $this->render('info/contact.html.twig');
    }
}
