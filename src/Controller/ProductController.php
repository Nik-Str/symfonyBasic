<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Session;

class ProductController extends AbstractController
{

  private $em;
  private $productRepository;
  private $userRepository;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
    $this->productRepository = $this->em->getRepository(Products::class);
    $this->userRepository = $this->em->getRepository(User::class);
  }

  #[Route('/product/{id}', name: 'app_product')]
  public function index($id, Session $session): Response | RedirectResponse | JsonResponse
  {
    try {
      if (!isset($_COOKIE['symfonyBasic'])) return $session->redirectResponse();

      $userSession = $session->validate($_COOKIE['symfonyBasic']);
      $userExist = $this->userRepository->findOneBy(['auth' => $userSession['user_id']]);

      if (!$userExist) return $session->redirectResponse();

      $product = $this->productRepository->find($id);

      return $this->render('product/index.html.twig', [
        'product' => $product,
        'links' => [['name' => 'Home', 'href' => '/'], ['name' => 'Products', 'href' => '/products/male']]
      ]);
    } catch (\Throwable $err) {
      $response = new JsonResponse();
      return $response->setStatusCode(500)->setData([
        'error' => $err->getMessage(),
      ])->send();
    }
  }
}
