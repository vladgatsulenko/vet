<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductManualRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Enum\Role;

#[Route('/product')]
final class ProductController extends AbstractController
{
    private const MAX_SEARCH_LENGTH = 255;

    #[Route(name: 'app_product_index', methods: [Request::METHOD_GET])]
    public function index(
        ProductRepository $productRepository,
        #[MapQueryParameter] ?string $search = null
    ): Response {
        $search = $this->normalizeSearch($search);

        $products = $productRepository->search($search);

        if ($search !== null && count($products) === 1) {
            return $this->redirectToRoute('app_product_show', ['id' => $products[0]->getId()]);
        }

        return $this->render('product/index.html.twig', [
        'products' => $products,
        'search' => $search,
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(Role::ADMIN->value);

        $form = $this->createForm(ProductType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Product $product */
            $product = $form->getData();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: [Request::METHOD_GET])]
    public function show(Product $product, ProductManualRepository $manualRepository): Response
    {
        $manual = $manualRepository->findOneByProduct($product);

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'manual'  => $manual,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(Role::ADMIN->value);

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods:[Request::METHOD_POST])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(Role::ADMIN->value);

        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }

    private function normalizeSearch(?string $search): ?string
    {
        if ($search === null) {
            return null;
        }

        $search = trim($search);
        if ($search === '') {
            return null;
        }

        if (mb_strlen($search) > self::MAX_SEARCH_LENGTH) {
            throw new BadRequestHttpException(sprintf(
                'Search query is too long (max %d characters).',
                self::MAX_SEARCH_LENGTH
            ));
        }

        return $search;
    }
}
