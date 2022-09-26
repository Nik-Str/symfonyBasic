<?php

namespace App\Service;

use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;

class Auth
{
  private $bcrypt;

  function __construct()
  {
    $factory = new PasswordHasherFactory([
      'common' => ['algorithm' => 'bcrypt'],
    ]);

    $this->bcrypt = $factory->getPasswordHasher('common');
  }

  public function passwordHash($password)
  {
    $hash = $this->bcrypt->hash($password);
    return $hash;
  }

  public function passwordAuth($password, $hash)
  {
    $verify = $this->bcrypt->verify($hash, $password);
    return $verify;
  }
}
