<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/product/add/{name}/{price}/{stock}', name: 'app_product_add')]
    public function addProduct(EntityManagerInterface $entityManager, string $name, float $price, int $stock): Response
    {   
        $product = new Product();
        $product->setName($name);
        $product->setPrice($price);
        $product->setStock($stock);
        $product->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($product);
        $entityManager->flush();

        return new Response('Produit ajouté avec l\'id '.$product->getId());
    }

    #[Route('/product/ajout10', name: 'app_product_ajout10')]
    public function ajout10stocks(ProductRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $strStock = '';
        $products = $repository->findAll(); //on récupère tous les produits
        foreach ($products as $product) { //pour chaque produit
            $product->setStock($product->getStock() + 10); 
            $entityManager->persist($product); //on met à jour le produit
            $strStock = $strStock.' '.$product->getStock();
        }
        $entityManager->flush(); //on flush pour enregistrer les changements
        return new Response('10 stocks ajoutés à tous les produits : '.$strStock);
    }

    #[Route('/product/afficher/{id}', name: 'app_product_afficher')]
    public function afficherProduits(ProductRepository $repository, int $id): Response
    {
        $products = $repository->findAll();

        return $this->render('product/afficher.html.twig', [
            'products' => $products,
        ]);
    }
}
