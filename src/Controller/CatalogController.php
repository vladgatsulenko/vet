<?php

namespace App\Controller;

use App\Dto\CatalogQuery;
use App\Repository\ProductRepository;
use App\Repository\PharmacologicalGroupRepository;
use App\Repository\AnimalSpeciesRepository;
use App\Repository\ManufacturerRepository;
use App\Service\Paginator;
use App\ViewModel\CatalogViewModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

#[Route('/catalog')]
final class CatalogController extends AbstractController
{
    #[Route('/', name: 'app_catalog_index', methods: ['GET'])]
    public function index(
        #[MapQueryString] CatalogQuery $query,
        ProductRepository $productRepository,
        PharmacologicalGroupRepository $groupRepository,
        AnimalSpeciesRepository $speciesRepository,
        ManufacturerRepository $manufacturerRepository,
        Paginator $paginator
    ): Response {
        $total = $productRepository->countBySearch(
            $query->search,
            $query->group,
            $query->species,
            $query->manufacturers
        );

        $pagination = $paginator->paginate($total, $query->page, $query->limit);

        $products = $productRepository->findPaginatedBySearch(
            $pagination->offset,
            $pagination->limit,
            $query->search,
            $query->group,
            $query->species,
            $query->manufacturers
        );

        $groups = $groupRepository->findAllOrderedByName();
        $species = $speciesRepository->findAllOrderedByName();
        $manufacturers = $manufacturerRepository->findAllOrderedByName();

        $model = new CatalogViewModel(
            $products,
            $query->search,
            $groups,
            $species,
            $manufacturers,
            $query->group,
            $query->species,
            $query->manufacturers,
            $pagination
        );

        return $this->render('catalog/index.html.twig', [
            'model' => $model,
        ]);
    }
}
