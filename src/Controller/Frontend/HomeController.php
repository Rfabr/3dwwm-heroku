<?php

namespace App\Controller\Frontend;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="app_frontend_home_index")
     */
    public function index(): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findByIsBest(1);

        return $this->render('frontend/home/index.html.twig', [
            'products' => $products
        ]);
    }
}
