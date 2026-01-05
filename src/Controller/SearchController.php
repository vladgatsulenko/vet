<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/search')]
final class SearchController extends AbstractController
{
    #[Route('/suggest', name: 'app_search_suggest', methods: ['GET'])]
    public function suggest(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $q = trim((string) $request->query->get('q', ''));

        if ($q === '') {
            return $this->json([]);
        }

        $products = $productRepository->findSuggestions($q, 10);

        $data = [];
        foreach ($products as $p) {
            $data[] = [
                'id' => $p->getId(),
                'name' => $p->getName(),
                'url' => $this->generateUrl('app_product_show', ['id' => $p->getId()]),
            ];
        }

        return $this->json($data);
    }
}
