<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Session;

class ProductsController extends AbstractController
{

	private $em;
	private $productRepository;
	private $userRepository;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
		$this->productRepository = $this->em->getRepository(Products::class);
		$this->userRepository = $this->em->getRepository(User::class);
	}

	#[Route('/products/{sex}', name: 'app_products')]
	public function index($sex, Session $session): Response | RedirectResponse | JsonResponse
	{
		function redirectResponse()
		{
			$response = new RedirectResponse($_ENV['BASE_URL'] . '/login');
			$response->send();
		}

		try {
			if (!isset($_COOKIE['symfonyBasic'])) return redirectResponse();

			$userSession = $session->validate($_COOKIE['symfonyBasic']);
			$userExist = $this->userRepository->findOneBy(['auth' => $userSession['user_id']]);

			if (!$userExist) return redirectResponse();

			$products = $this->productRepository->findAll();

			return $this->render('products/index.html.twig', [
				'products' => $products,
				'sex' => $sex
			]);
		} catch (\Throwable $err) {
			$response = new JsonResponse();
			return $response->setStatusCode(500)->setData([
				'error' => $err->getMessage(),
			])->send();
		}
	}
}
