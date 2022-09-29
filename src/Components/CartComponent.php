<?php

namespace App\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use App\Repository\ProductsRepository;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

#[AsLiveComponent('cart', csrf: false)]
class CartComponent
{
  use DefaultActionTrait;

  public $cartItems;
  public $productsRepository;
  public $selectedId;
  public $isShowing = "";

  public function __construct(ProductsRepository $productsRepository)
  {
    $this->productsRepository = $productsRepository;
    $this->getCart();
  }

  public function getCart()
  {
    if (isset($_COOKIE['symfonyCart'])) {
      $this->cookieCart = json_decode($_COOKIE['symfonyCart'], true);
      $this->cartItems = array_map("self::getProduct", $this->cookieCart);
    }
  }

  public function getProduct($item)
  {
    $product = $this->productsRepository->find($item['id']);

    $title = $product->getTitle();
    $brand = $product->getBrand();
    $price = $product->getPrice();
    $image = $product->getImage();

    return ['id' => $item['id'], 'title' => $title, 'brand' => $brand, 'price' => $price, 'image' => $image, 'amount' => $item['amount']];
  }

  #[LiveAction]
  public function increment(#[LiveArg] int $id)
  {
    $this->selectedId = $id;
    $this->cartItems = array_map("self::inc", $this->cartItems);
    $this->isShowing = "show";
  }
  public function inc($item)
  {
    if ($item['id'] == $this->selectedId) return [...$item, 'amount' => ++$item['amount']];
    else return $item;
  }
}
