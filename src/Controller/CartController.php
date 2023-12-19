<?php

namespace App\Controller;

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
use App\Service\CartService;
use App\Repository\CartItemRepository;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/cart', name: 'cart_', methods: ['GET','POST'])]
class CartController extends AbstractController
{

    #[Route('/add/{prodId}', name: 'add', methods: ['GET'])]
    public function add(int $prodId, CartService $cartService, CartItemRepository $cartItemRepository): Response
    {   
        $cart=$cartService->add($prodId);
        //dd($cart);
        /**  @var User $user */
        $user = $this->getUser(); 
        //dd($cartItemRepository->get($user));
        return $this->render('cart/index.html.twig', [
            'cartItem' => $cartItemRepository->get($user),
            //'cart' => $cart,
        ]);      
    }


}