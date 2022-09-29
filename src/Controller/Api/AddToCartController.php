<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;

class AddToCartController extends AbstractController
{
  #[Route('/api/addtocart/{id}', methods: ['GET'])]
  public function index($id): RedirectResponse | JsonResponse
  {
    try {
      $expiration = time() + 3600 * 24;

      $response = new RedirectResponse($_ENV['BASE_URL'] . '/product/' . $id);

      $cartItems = [];
      if (isset($_COOKIE['symfonyCart'])) {
        $cart = json_decode($_COOKIE['symfonyCart'], true);
        $cartItems = [...$cart];
      }

      $existInCart = false;
      for ($i = 0; $i < count($cartItems); $i++) {
        $article_id = $cartItems[$i]['id'];

        if ($article_id === $id) {
          $cartItems[$i]['amount'] = ++$cartItems[$i]['amount'];
          $existInCart = true;
        }
      }
      if (!$existInCart) array_push($cartItems, ['id' => $id, 'amount' => 1]);

      $response->headers->setCookie(Cookie::create('symfonyCart')->withValue(json_encode($cartItems))->withExpires($expiration));

      $response->send();
    } catch (\Throwable $err) {
      $response = new JsonResponse();
      return $response->setStatusCode(500)->setData([
        'error' => $err->getMessage(),
      ])->send();
    }
  }
}
