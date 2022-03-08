<?php

declare(strict_types=1);

namespace App\Class;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $session;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function get()
    {
        return $this->session->get('cart');
    }

    public function getFull()
    {
        $cartComplete = [];

        if($this->get())
        {
            foreach($this->get() as $id => $quantity)
            {
                $cartComplete[] = [
                    'product' => $this->entityManager->getRepository(Product::class)->findOneById($id),
                    'quantity' => $quantity
                ];
            }
        }

        return $cartComplete;
    }

    public function remove()
    {
        return $this->session->remove('cart');
    }
}
