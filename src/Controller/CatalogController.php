<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\PharmacologicalGroupRepository;
use App\Repository\AnimalSpeciesRepository;
use App\Repository\ManufacturerRepository;
use App\Service\Paginator;
use App\ViewModel\CatalogViewModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/catalog')]
final class CatalogController extends AbstractController
{
    private const DEFAULT_PAGE = 1;
    private const PER_PAGE = 12;

    #[Route('/', name: 'app_catalog_index', methods: ['GET'])]
    public function index(
        Request $request,
        ProductRepository $productRepository,
        PharmacologicalGroupRepository $groupRepository,
        AnimalSpeciesRepository $speciesRepository,
        ManufacturerRepository $manufacturerRepository,
        Paginator $paginator
    ): Response {
        $search = $request->query->get('search', '') !== '' ? trim((string) $request->query->get('search')) : null;
        $rawGroup = $request->query->get('group', null);
        $rawSpecies = $request->query->get('species', null);

        $rawManufacturers = $request->query->all('manufacturers');
        if (!is_array($rawManufacturers)) {
            $rawManufacturers = $rawManufacturers === null ? [] : (array) $rawManufacturers;
        }

        $manufacturerIds = array_values(array_filter(array_map(
            fn($v) => filter_var($v, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE),
            $rawManufacturers
        ), fn($v) => $v !== null));

        $groupId = filter_var($rawGroup, FILTER_VALIDATE_INT, ['flags' => FILTER_NULL_ON_FAILURE]);
        $speciesId = filter_var($rawSpecies, FILTER_VALIDATE_INT, ['flags' => FILTER_NULL_ON_FAILURE]);

        $page = max(self::DEFAULT_PAGE, $request->query->getInt('page', self::DEFAULT_PAGE));
        $perPage = self::PER_PAGE;

        $total = $productRepository->countBySearch($search, $groupId, $speciesId, $manufacturerIds);

        $pagination = $paginator->paginate($total, $page, $perPage);

        $products = $productRepository->findPaginatedBySearch(
            $pagination->offset,
            $pagination->limit,
            $search,
            $groupId,
            $speciesId,
            $manufacturerIds
        );

        $groups = $groupRepository->findAllOrderedByName();
        $species = $speciesRepository->findAllOrderedByName();
        $manufacturers = $manufacturerRepository->findAllOrderedByName();

        $model = new CatalogViewModel(
            $products,
            $search,
            $groups,
            $species,
            $manufacturers,
            $groupId,
            $speciesId,
            $manufacturerIds,
            $pagination
        );

        return $this->render('catalog/index.html.twig', [
            'model' => $model,
        ]);
    }
}
