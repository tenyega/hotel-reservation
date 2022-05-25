<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Stripe\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerController extends AbstractController
{
    /**
     * @Route("/reservation/{roomNo}", name="reservation_customerDetailForm")
     */

    public function customerDetailForm($roomNo, Request $request, EntityManagerInterface $em, StripeService $stripeService)
    {
        $customer = new Customer;
        $form = $this->createForm(CustomerType::class, $customer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($customer);
            $em->flush();
            $data = $form->getData();
            dump($data);
            dump('form submitted');
            return $this->redirectToRoute('reservation_payment', [
                'roomNo' => $roomNo
            ]);
        }

        return $this->render('front/reservation/customerDetailForm.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
