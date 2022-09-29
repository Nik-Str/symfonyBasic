<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UpdateCartController extends AbstractController
{
  public $body;
  public $cookie;

  #[Route('/api/updatecart', methods: ['POST'])]
  public function index(Request $request): JsonResponse
  {
    try {
      // id & type "inc" / "dec"
      $this->body = json_decode($request->getContent(), true);

      if (isset($_COOKIE['symfonyCart'])) {
        $this->cookie = json_decode($_COOKIE['symfonyCart'], true);
      }

      if ($this->body['type'] === 'inc') $this->increment();
      if ($this->body['type'] === 'dec') $this->decrement();

      $response = new JsonResponse();
      $expiration = time() + 3600 * 24;
      $response->headers->setCookie(Cookie::create('symfonyCart')->withValue(json_encode($this->cookie))->withExpires($expiration));

      $response->setStatusCode(200)->setData([
        'data' => $this->cookie,
      ])->send();
    } catch (\Throwable $err) {
      $response = new JsonResponse();
      return $response->setStatusCode(500)->setData([
        'error' => $err->getMessage(),
      ])->send();
    }
  }

  public function increment()
  {
    for ($i = 0; $i < count($this->cookie); $i++) {
      if ($this->cookie[$i]['id'] == $this->body['id']) {
        $this->cookie[$i]['amount'] = ++$this->cookie[$i]['amount'];
      }
    }
  }

  public function decrement()
  {
    for ($i = 0; $i < count($this->cookie); $i++) {
      if ($this->cookie[$i]['id'] == $this->body['id']) {
        if ($this->cookie[$i]['amount'] == 1) array_splice($this->cookie, $i, 1);
        else  $this->cookie[$i]['amount'] = --$this->cookie[$i]['amount'];
      }
    }
  }
}
