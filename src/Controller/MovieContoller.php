<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieContoller extends AbstractController
{

  private $em;
  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  #[Route('/movie', name: 'app_movie_contoller')]
  public function index(): Response
  {

    $repository = $this->em->getRepository(Movie::class);
    $movies = $repository->findAll();

    return $this->render('index.html.twig', ['movies' => $movies]);
  }

  #[Route('/actor', name: 'app_actor_contoller')]
  public function actor(): Response
  {

    $repository = $this->em->getRepository(Actor::class);
    $movies = $repository->findAll();
    dd($movies);

    return $this->render('index.html.twig');
  }
}
