<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use App\Service\Session;

#[AsTwigComponent('header')]
class HeaderComponent
{
  public $isLoggedIn = false;

  public function __construct(Session $session)
  {
    if (isset($_COOKIE['symfonyBasic'])) {
      $userSession = $session->validate($_COOKIE['symfonyBasic']);
      if ($userSession) $this->isLoggedIn = true;
    }
  }
}
