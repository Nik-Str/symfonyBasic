<?php

namespace App\Service;

use ReallySimpleJWT\Token;

// https://github.com/RobDWaller/ReallySimpleJWT
class Session
{

  static function create($user_auth, $expiration)
  {
    $issuer = $_ENV['BASE_URL'];

    return Token::create($user_auth, $_ENV['JWT_SECRET'], $expiration, $issuer);
  }

  static function validate($jwt)
  {

    try {
      if (Token::validate($jwt, $_ENV['JWT_SECRET'])) return Token::getPayload($jwt);
    } catch (\Throwable $err) {
      return false;
    }
  }
}
