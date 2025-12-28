<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\PharmacologicalGroupRepository;
use App\Repository\AnimalSpeciesRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/catalog')]
final class CatalogController extends AbstractController
{
    #[Route('/', name: 'app_catalog_index', methods: ['GET'])]
    public function index(
        Request $request,
        ProductRepository $productRepository,
        PharmacologicalGroupRepository $groupRepository,
        AnimalSpeciesRepository $speciesRepository
    ): Response {
        $search = $request->query->get('search', '') !== '' ? trim((string)$request->query->get('search')) : null;
        $groupId = $request->query->getInt('group') ?: null;
        $speciesId = $request->query->getInt('species') ?: null;

        $page = max(1, $request->query->getInt('page', 1));
        $perPage = 12; // rконстанту сделать

        $qb = $productRepository->createSearchQueryBuilder($search, $groupId, $speciesId);

        $query = $qb
            ->getQuery()
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        $paginator = new Paginator($query, true);
        $total = count($paginator);
        $pages = (int) ceil($total / $perPage);

        // need paginator
        $products = iterator_to_array($paginator);

        $groups = $groupRepository->findAll();
        $species = $speciesRepository->findAll();

        return $this->render('catalog/index.html.twig', [
            'products' => $products,
            'search' => $search,
            'groups' => $groups,
            'species' => $species,
            'selectedGroup' => $groupId,
            'selectedSpecies' => $speciesId,
            'page' => $page,
            'pages' => $pages,
            'total' => $total,
        ]);
    }
}

