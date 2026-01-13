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
        $group = null;
        if ($query->group !== null) {
            $group = $groupRepository->find($query->group);
            if ($group === null) {
                throw $this->createNotFoundException('Pharmacological group not found.');
            }
        }

        $species = null;
        if ($query->species !== null) {
            $species = $speciesRepository->find($query->species);
            if ($species === null) {
                throw $this->createNotFoundException('Animal species not found.');
            }
        }

        $manufacturers = [];
        if (!empty($query->manufacturers)) {
            $manufacturers = $manufacturerRepository->findBy(['id' => $query->manufacturers]);

            if (count($manufacturers) !== count(array_unique($query->manufacturers))) {
                throw $this->createNotFoundException('One or more manufacturers not found.');
            }
        }

        $total = $productRepository->countBySearch(
            $query->search,
            $group,
            $species,
            $manufacturers
        );

        $pagination = $paginator->paginate($total, $query->page, $query->limit);

        $products = $productRepository->findPaginatedBySearch(
            $pagination->offset,
            $pagination->limit,
            $query->search,
            $group,
            $species,
            $manufacturers
        );

        $groups = $groupRepository->findAllOrderedByName();
        $speciesList = $speciesRepository->findAllOrderedByName();
        $manufacturersList = $manufacturerRepository->findAllOrderedByName();

        $model = new CatalogViewModel(
            $products,
            $query->search,
            $groups,
            $speciesList,
            $manufacturersList,
            $group?->getId(),
            $species?->getId(),
            array_map(fn($m) => $m->getId(), $manufacturers),
            $pagination
        );

        return $this->render('catalog/index.html.twig', [
            'model' => $model,
        ]);
    }
}
