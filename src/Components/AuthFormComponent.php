<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('authForm')]
class AuthFormComponent
{
  public $controller;
  public $formAction;
  public $inputAction;
  public $email;
  public $password;
  public $emailFeedback;
}
