<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('toast')]
class ToastComponent
{
  public $controller;
  public $toast;
  public $body;
}
