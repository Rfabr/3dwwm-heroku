<?php

namespace App\Controller\Frontend\Account\Order;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountOrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/mon-compte/mes-commandes", name="app_frontend_account_order_index")
     * @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
        $orders = $this->entityManager->getRepository(Order::class)->findLast($this->getUser());

        return $this->render('frontend/account/order/index.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/mon-compte/commande/{id}", name="app_frontend_account_order_display")
     * @IsGranted("ROLE_USER")
     */
    public function display(Order $order): Response
    {
        if(!$order || $order->getUser() != $this->getUser())
        {
            return $this->redirectToRoute('app_frontend_account_order_index');
        }

        return $this->render('frontend/account/order/display.html.twig', [
            'order' => $order
        ]);
    }
}
