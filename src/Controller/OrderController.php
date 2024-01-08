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
use App\Repository\CartItemRepository;
use App\Entity\User;
use App\Repository\CartRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\AddressType;
use App\Service\AddressService;
use App\Service\ShipmentService;
use App\Service\OrderService;


#[Route('/order', name: 'order_', methods: ['GET','POST'])]
class OrderController extends AbstractController
{
    #[Route('/created', name: 'created', methods: ['GET', 'POST'])]
    public function placeOrder(
        CartService $cartService, 
        AddressService $addressService, 
        ShipmentService $shipmentService, 
        CartCalculatorService $cartCalculatorService,
        OrderService $orderService
    ): Response
    {
        $orderService->createPending();
        return new Response('ok');
    }

}