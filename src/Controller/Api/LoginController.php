<?php

namespace App\Controller\Api;

use App\Service\Auth;
use App\Service\Session;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends AbstractController
{
  #[Route('/api/login', methods: ['POST'])]
  public function index(Request $request, UserRepository $userRepository, Session $session): JsonResponse
  {
    try {

      function invalidResponse($response)
      {
        return $response->setStatusCode(409)->setData([
          'error' => 'Invalid Email or Password',
        ])->send();
      }

      $body = json_decode($request->getContent(), true);
      $response = new JsonResponse();

      $userExist = $userRepository->findOneBy(['email' => $body['email']]);
      if (!$userExist) invalidResponse($response);

      $authService = new Auth();

      $verify = $authService->passwordAuth($body['password'], $userExist->getPassword());
      if (!$verify) invalidResponse($response);

      $expiration = time() + 3600 * 24;
      $token = $session->create($userExist->getAuth(), $expiration);

      $response->headers->setCookie(Cookie::create('symfonyBasic')->withValue($token)->withExpires($expiration));
      return $response->setStatusCode(201)->setData([
        'data' => 'User created',
      ])->send();
    } catch (\Throwable $err) {
      return $response->setStatusCode(500)->setData([
        'error' => $err->getMessage(),
      ])->send();
    }
  }
}
