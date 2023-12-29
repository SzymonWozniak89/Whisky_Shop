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
use App\Service\CartService;
use App\Repository\CartItemRepository;
use App\Entity\User;
use App\Repository\CartRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\AddressType;
use App\Service\AddressService;

#[Route('/checkout', name: 'checkout_', methods: ['GET','POST'])]
class CheckoutController extends AbstractController
{
    #[Route('/show', name: 'show', methods: ['GET', 'POST'])]
    public function checkout(CartService $cartService, AddressService $addressService): Response
    {
        //dd($addressService->getUserAddresses());
        return $this->render('checkout/index.html.twig', [
            'addresses' => $addressService->getUserAddresses(),
            'cartItems' => $cartService->getCartItems(),
            'totalPrice' => $cartService->getTotalPrice(),
            'subtotalPrice' => $cartService->getSubtotalPrice(),
            'shippingPrice' => $cartService->getShippingPrice(),
        ]); 
    }

}