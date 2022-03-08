<?php

namespace App\Controller\Frontend;

use App\Class\Cart;
use App\Class\Mail;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\Frontend\OrderType;
use App\Form\Frontend\CreditCardType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/commande/details-expedition", name="app_frontend_order_delivery")
     * @IsGranted("ROLE_USER")
     */
    public function delivery(Cart $cart): Response
    {
        if(!$this->getUser()->getAddresses()->getValues())
        {
            return $this->redirectToRoute('app_frontend_account_address_add');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('frontend/order/delivery.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    /**
     * @Route("/commande/recapitulatif", name="app_frontend_order_add", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function add(Cart $cart, Request $request): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $date = new \DateTime();
            $carriers = $form->get('carriers')
                             ->getData();
            $delivery = $form->get('addresses')
                             ->getData();
            $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();
            $delivery_content .= '<br/>'.$delivery->getAddress();
            $delivery_content .= '<br/>'.$delivery->getPostal().' '.$delivery->getCity();
            $delivery_content .= '<br/>'.$delivery->getCountry();
            $delivery_content .= '<br/>'.$delivery->getPhone();

            //Register my order : Order
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);

            $this->entityManager->persist($order);

            //Register my products : OrderDetails
            foreach($cart->getFull() as $product)
            {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);

                $this->entityManager->persist($orderDetails);
            }

            $this->entityManager->flush();

            return $this->render('frontend/order/add.html.twig', [
                'cart' => $cart->getFull(),
                'carrier' => $carriers,
                'delivery' => $delivery_content,
                'id' => $order->getId()
            ]);
        }

        return $this->redirectToRoute('app_frontend_cart_index');
    }

    /**
     * @Route("/commande/paiement/{id}", name="app_frontend_order_pay")
     * @IsGranted("ROLE_USER")
     */
    public function pay(Request $request, Order $order): Response
    {
        $form = $this->createForm(CreditCardType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            return $this->redirectToRoute('app_frontend_order_confirm', [
                'id' => $order->getId()
            ]);
        }

        return $this->render('frontend/order/pay.html.twig', [
            'form' => $form->createView(),
            'order' => $order
        ]);
    }

    /**
     * @Route("/commande/confirmation/{id}", name="app_frontend_order_confirm")
     * @IsGranted("ROLE_USER")
     */
    public function confirm(Order $order, Cart $cart): Response
    {
        if(!$order || $order->getUser() != $this->getUser())
        {
            return $this->redirectToRoute('app_frontend_home_index');
        }

        if(!$order->getIsPaid())
        {
            $cart->remove();

            $order->setIsPaid(1);
            $this->entityManager->flush();

            $mail = new Mail();

            $content = "Bonjour".$order->getUser()->getFirstname()."<br>Merci pour votre commande chez 3DWWM, le spécialiste de l'impression 3D pour tous !<br><br>Votre commande a bien été prise en compte, vous pouvez désormais vous connecter à votre compte pour suivre sa progression.<br><br>À bientôt !";

            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Validation commande 3DWWM', $content);
        }

        return $this->render('frontend/order/success.html.twig', [
            'order' => $order
        ]);
    }

    /**
     * @Route("/commande/erreur/{id}", name="app_frontend_order_error")
     * @IsGranted("ROLE_USER")
     */
    public function error(Order $order): Response
    {
        if(!$order || $order->getUser() != $this->getUser())
        {
            return $this->redirectToRoute('app_frontend_home_index');
        }

        $mail = new Mail();

        $content = "Bonjour".$order->getUser()->getFirstname()."<br>Il semblerait qu'une erreur se soit produite lors du paiement de votre commande.<br><br>Nous vous invitons à réessayer votre paiement en cliquant sur le lien 'Veuillez réessayer' de la page d'erreur.<br><br>À très vite !";

        $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Erreur paiement 3DWWM', $content);

        return $this->render('frontend/order/cancel.html.twig', [
            'order' => $order
        ]);
    }
}
