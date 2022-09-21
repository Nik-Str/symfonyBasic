<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SignupController extends AbstractController
{
	#[Route('/api/signup', methods: ['POST'])]
	public function index(Request $request): Response
	{

		$body = json_decode($request->getContent(), true);

		$data = [
			'email' => $body['email'],
			'password' => $body['password'],
		];

		return $this->json($data);
	}
}
