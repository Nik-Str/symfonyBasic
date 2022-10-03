<?php

namespace App\Controller;

use App\Entity\Orders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfirmationController extends AbstractController
{
  private $em;
  private $orderRepository;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
    $this->orderRepository = $this->em->getRepository(Orders::class);
  }

  #[Route('/confirmation/{id}', name: 'app_confirmation')]
  public function index($id): Response
  {

    $order = $this->orderRepository->find($id);

    //Get Product info
    $orderDetails = $order->getOrderDetails();
    $orderList = $orderDetails->getValues(); //Get Values is called to return each orderDetails entity in array format

    $products = array_map('self::getOrderInfo', $orderList);

    //Get User info
    $userEmail = $order->getUser()->getEmail();

    // Get Total
    $total = array_reduce(array: $products, callback: fn ($total, $item) => $total += ($item['price'] * $item['amount']));

    $info = ['products' => $products, 'user' => $userEmail, 'total' => $total];

    return $this->render('confirmation/index.html.twig', [
      'orderDetails' => $info,
    ]);
  }

  public function getOrderInfo($orderDetails)
  {
    $product = $orderDetails->getProduct();
    $id = $product->getId();
    $title = $product->getTitle();
    $brand = $product->getBrand();
    $price = $product->getPrice();
    $image = $product->getImage();

    return ['id' => $id, 'title' => $title, 'brand' => $brand, 'price' => $price, 'image' => $image, 'amount' => $orderDetails->getAmount()];
  }
}
