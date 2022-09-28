<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use App\Service\Session;

#[AsTwigComponent('header')]
class HeaderComponent
{
  public $isLoggedIn = false;
  public $cartItems = "0";

  public function __construct(Session $session)
  {
    if (isset($_COOKIE['symfonyBasic'])) {
      $userSession = $session->validate($_COOKIE['symfonyBasic']);
      if ($userSession) $this->isLoggedIn = true;
    }

    if (isset($_COOKIE['symfonyCart'])) {
      $cart = json_decode($_COOKIE['symfonyCart'], true);
      $this->cartItems = strval(count($cart));
    }
  }
}
