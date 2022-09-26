<?php

namespace App\Controller\Api;

use App\Service\Auth;
use App\Service\Session;
use App\Entity\User;
use Symfony\Component\Uid\Uuid;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SignupController extends AbstractController
{
	#[Route('/api/signup', methods: ['POST'])]
	public function index(Request $request, ManagerRegistry $doctrine, UserRepository $userRepository, Session $session): JsonResponse
	{
		try {

			$body = json_decode($request->getContent(), true);
			$response = new JsonResponse();

			$userExist = $userRepository->findOneBy(['email' => $body['email']]);

			if ($userExist) {
				return $response->setStatusCode(409)->setData([
					'error' => 'User email already registered',
				])->send();
			}

			$authService = new Auth();
			$user = new User();
			$user->setEmail($body['email']);

			$uuid = Uuid::v4();
			$user->setAuth($uuid);

			$hash = $authService->passwordHash($body['password']);
			$user->setPassword($hash);

			$entityManager = $doctrine->getManager();
			$entityManager->persist($user);
			$entityManager->flush();

			$expiration = time() + 3600 * 24;
			$token = $session->create($uuid, $expiration);

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
