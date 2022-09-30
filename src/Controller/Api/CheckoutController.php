<?php

namespace App\Controller\Api;

use App\Entity\OrderDetails;
use App\Entity\Orders;
use App\Entity\Products;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Service\Session;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class CheckoutController extends AbstractController
{

  private $em;
  private $productRepository;
  private $userRepository;
  private $orderRepository;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
    $this->productRepository = $this->em->getRepository(Products::class);
    $this->userRepository = $this->em->getRepository(User::class);
    $this->orderRepository = $this->em->getRepository(Orders::class);
  }

  #[Route('/api/checkout', methods: ['GET'])]
  public function index(Session $session, ManagerRegistry $doctrine): JsonResponse | RedirectResponse
  {
    try {
      $entityManager = $doctrine->getManager();

      //Get User
      $userSession = $session->validate($_COOKIE['symfonyBasic']);
      $user = $this->userRepository->findOneBy(['auth' => $userSession['user_id']]);

      //Create Order
      $order = new Orders();
      $order->setUser($user);
      $entityManager->persist($order);
      $entityManager->flush();

      //Get Cart
      $cart = json_decode($_COOKIE['symfonyCart'], true);
      $orderLink = $this->orderRepository->find($order->getId());
      $getProduct = fn (int $cartItem) => $this->productRepository->find($cartItem);


      //Create OrderDetails
      foreach ($cart as $item) {
        $orderDetails = new OrderDetails();
        $product = $getProduct($item['id']);

        $orderDetails->setProduct($product);
        $orderDetails->setOrder($orderLink);
        $orderDetails->setAmount($item['amount']);

        $entityManager->persist($orderDetails);
      }

      $entityManager->flush();

      $response = new RedirectResponse($_ENV['BASE_URL'] . '/confirmation/' .  $order->getId());
      $response->send();
    } catch (\Throwable $err) {
      $response = new JsonResponse();
      return $response->setStatusCode(500)->setData([
        'error' => $err->getMessage(),
      ])->send();
    }
  }
}
