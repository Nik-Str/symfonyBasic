<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('authForm')]
class AuthFormComponent
{
  public $controller;
  public $action;
  public $email;
  public $password;
}
