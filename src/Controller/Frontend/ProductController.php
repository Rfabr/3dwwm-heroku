<?php

namespace App\Controller\Frontend;

use App\Entity\Product;
use App\Form\Frontend\ProductSearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/nos-produits", name="app_frontend_product_index")
     */
    public function index(Request $request): Response
    {
        $repository = $this->getDoctrine()
                           ->getRepository(Product::class);

        $form = $this->createForm(ProductSearchType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $products = $repository->findWithSearch($form->getData());
        }
        else
        {
            $products = $repository->findAll();
        }

        return $this->render('frontend/product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/produit/{slug}", name="app_frontend_product_display")
     */
    public function display(Product $product): Response
    {
        return $this->render('frontend/product/display.html.twig', [
            'product' => $product
        ]);
    }
}
