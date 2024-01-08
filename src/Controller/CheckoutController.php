<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\CartItem;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Service\ProductService;
use App\Service\CartItemService;
use App\Service\CartCalculatorService;
use App\Service\CartService;
use App\Service\PaymentService;
use App\Repository\CartItemRepository;
use App\Entity\User;
use App\Repository\CartRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\AddressType;
use App\Service\AddressService;
use App\Service\ShipmentService;


#[Route('/checkout', name: 'checkout_', methods: ['GET','POST'])]
class CheckoutController extends AbstractController
{
    #[Route('/show', name: 'show', methods: ['GET', 'POST'])]
    public function checkout(CartService $cartService, AddressService $addressService, PaymentService $paymentService, ShipmentService $shipmentService, CartCalculatorService $cartCalculatorService): Response
    {
        return $this->render('checkout/index.html.twig', [
            'cart' => $cartService->findUserCart(),
            'address' => $addressService->getShippingAddress(),
            'cartItems' => $cartService->getCartItems(),
            'shipping' => $shipmentService->getShipping(),
            'payment' => $paymentService->getPayment(),
            'totalPrice' => $cartCalculatorService->getOrderTotalPrice(),
            'subtotalPrice' => $cartService->getSubtotalPrice(),
            'shippingPrice' => $cartService->getShippingPrice(),
        ]); 
    }

    #[Route('/shipping/{id}', name: 'shipping', methods: ['GET', 'POST'])]
    public function setShippingMethod(int $id, ShipmentService $shipmentService, CartCalculatorService $cartCalculatorService): JsonResponse
    {
        $shipmentService->add($id);  

        return new JsonResponse([
            'shippingPrice' => $shipmentService->getById($id)->getPrice(),
            'totalPrice' => $cartCalculatorService->getOrderTotalPrice(),
        ]);
    }

}