<?php

namespace App\Controller\Api;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class LogoutController extends AbstractController
{
	#[Route('/api/logout', methods: ['GET'])]
	public function index(): RedirectResponse | JsonResponse
	{
		try {
			$response = new RedirectResponse($_ENV['BASE_URL'] . '/');
      $response->headers->clearCookie('symfonyBasic');
			$response->send();
		} catch (\Throwable $err) {
      $response = new JsonResponse();
			return $response->setStatusCode(500)->setData([
				'error' => $err->getMessage(),
			])->send();
		}
	}
}