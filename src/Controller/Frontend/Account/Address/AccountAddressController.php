<?php

declare(strict_types=1);

namespace App\Controller\Frontend\Account\Address;

use App\Class\Cart;
use App\Entity\Address;
use App\Form\Frontend\AddressType;
use App\Repository\AddressRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountAddressController extends AbstractController
{
    /**
     * @Route("/mon-compte/mes-adresses", name="app_frontend_account_address_index")
     * @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
        return $this->render('frontend/account/address/index.html.twig');
    }

    /**
     * @Route("/mon-compte/ajouter-une-adresse", name="app_frontend_account_address_add")
     * @IsGranted("ROLE_USER")
     */
    public function add(Cart $cart, Request $request): Response
    {
        $address = new Address();

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $address->setUser($this->getUser());

            $manager = $this->getDoctrine()
                            ->getManager();

            $manager->persist($address);

            $manager->flush();

            if($cart->get())
            {
                return $this->redirectToRoute('app_frontend_order_delivery');
            }
            else
            {
                return $this->redirectToRoute('app_frontend_account_address_index');
            }
        }

        return $this->render('frontend/account/address/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/mon-compte/modifier-mon-adresse/{id}", name="app_frontend_account_address_update")
     * @IsGranted("ROLE_USER")
     */
    public function update(Request $request, $id, AddressRepository $addressRepository): Response
    {
        $address = $addressRepository->findOneById($id);

        if(!$address || $address->getUser() != $this->getUser())
        {
            return $this->redirectToRoute('app_frontend_account_address_index');
        }

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager = $this->getDoctrine()
                            ->getManager();

            $manager->flush();

            return $this->redirectToRoute('app_frontend_account_address_index');
        }

        return $this->render('frontend/account/address/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/mon-compte/delete-my-address/{id}", name="app_frontend_account_address_delete")
     * @IsGranted("ROLE_USER")
     */
    public function delete($id, AddressRepository $addressRepository): Response
    {
        $address = $addressRepository->findOneById($id);

        if($address && $address->getUser() == $this->getUser())
        {
            $manager = $this->getDoctrine()
                            ->getManager();

            $manager->remove($address);

            $manager->flush();
        }

        return $this->redirectToRoute('app_frontend_account_address_index');
    }
}
