<?php

namespace App\Controller;

use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{

    private $em;
    private $productRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->productRepository = $this->em->getRepository(Products::class);
    }

    #[Route('/products/{sex}', name: 'app_products')]
    public function index($sex): Response
    {

        $products = $this->productRepository->findAll();

        return $this->render('products/index.html.twig', [
            'products' => $products,
            'sex' => $sex
        ]);
    }
}
