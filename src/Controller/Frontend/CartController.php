<?php

namespace App\Controller\Frontend;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="app_frontend_cart_index")
     */
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        //Retrieving the current cart
        $cart = $session->get("cart", []);

        //Data manufacturing
        $dataCart = [];
        $total = 0;

        foreach($cart as $id => $quantity)
        {
            $product = $productRepository->find($id);
            $dataCart[] = [
                "product" => $product,
                "quantity" => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }

        return $this->render('frontend/cart/index.html.twig', compact("dataCart", "total"));
    }

    /**
     * @Route("/cart/add/{id}", name="app_frontend_cart_add")
     */
    public function add(Product $product, SessionInterface $session): Response
    {
        $cart = $session->get("cart", []);
        $id = $product->getId();

        if(!empty($cart[$id]))
        {
            $cart[$id]++;
        }
        else
        {
            $cart[$id] = 1;
        }

        //Backup in the session
        $session->set("cart", $cart);

        return $this->redirectToRoute('app_frontend_cart_index');
    }

    /**
     * @Route("/cart/remove/{id}", name="app_frontend_cart_remove")
     */
    public function remove(Product $product, SessionInterface $session): Response
    {
        $cart = $session->get("cart", []);
        $id = $product->getId();

        if(!empty($cart[$id]))
        {
            if($cart[$id] > 1)
            {
                $cart[$id]--;
            }
            else
            {
                unset($cart[$id]);
            }
        }

        $session->set("cart", $cart);

        return $this->redirectToRoute('app_frontend_cart_index');
    }

    /**
     * @Route("/cart/delete/{id}", name="app_frontend_cart_delete")
     */
    public function delete(Product $product, SessionInterface $session): Response
    {
        $cart = $session->get("cart", []);
        $id = $product->getId();

        if(!empty($cart[$id]))
        {
            unset($cart[$id]);
        }

        $session->set("cart", $cart);

        return $this->redirectToRoute('app_frontend_cart_index');
    }
}
