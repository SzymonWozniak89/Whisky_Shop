<?php

namespace App\Controller;

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

use function PHPUnit\Framework\returnSelf;
use function PHPUnit\Framework\returnValue;

#[Route('/cart', name: 'cart_', methods: ['GET','POST'])]
class CartController extends AbstractController
{

    #[Route('/add/{prodId}', name: 'add', methods: ['GET'])]
    public function add(int $prodId, CartService $cartService, Request $request): Response
    {   
        if ($request->isXmlHttpRequest()) {  
            if ($cartService->getItemQuantity($prodId) < $cartService->getProductStock($prodId)){
                $cartService->add($prodId);
                 
                return new JsonResponse([
                    'quantity' => $cartService->getItemQuantity($prodId), 
                    'price' => $cartService->getItemTotalPrice($prodId),
                    'totalPrice' => $cartService->getTotalPrice(),
                    'subtotalPrice' => $cartService->getSubtotalPrice(),
                    'shippingPrice' => $cartService->getShippingPrice(),
                    'productStock' => $cartService->getProductStock($prodId)
                ]);
            } else {
                return new JsonResponse([
                    'quantity' => $cartService->getItemQuantity($prodId), 
                    'price' => $cartService->getItemTotalPrice($prodId),
                    'totalPrice' => $cartService->getTotalPrice(),
                    'subtotalPrice' => $cartService->getSubtotalPrice(),
                    'shippingPrice' => $cartService->getShippingPrice(),
                    'productStock' => $cartService->getProductStock($prodId)
                ]);
            }
             
        } else {
            $cartService->add($prodId);  
            $this->addFlash('success', 'Product added'); 
            return $this->redirectToRoute('product');    
        } 

    }

    #[Route('/show', name: 'show', methods: ['GET'])]
    public function cart(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'cartItem' => $cartService->getCartItems(),
            'totalPrice' => $cartService->getTotalPrice(),
            'subtotalPrice' => $cartService->getSubtotalPrice(),
            'shippingPrice' => $cartService->getShippingPrice(),
        ]); 
    }

    #[Route('/remove/{id}', name: 'remove', methods: ['GET'])]
    public function remove(Request $request, CartItem $cartItem, CartService $cartService): Response
    {   
        if ($this->isCsrfTokenValid('delete'.$cartItem->getId(), $request->request->get('_token'))) {
            $cartService->remove($cartItem);
            $this->addFlash('success', 'Product removed');
        }
        return $this->redirectToRoute('cart_show');       
    }

    #[Route('/CartIcon', name: 'CartIcon', methods: ['GET'])]
    public function CartIcon(CartService $cartService): Response
    {
        return $this->render('partials/CartIcon.html.twig', [
            'numberOfProductsInCart'=>$cartService->getNumberOfProductsInCart()
        ]); 
    }

    #[Route('/sub/{prodId}', name: 'sub', methods: ['GET'])]
    public function sub(int $prodId, CartService $cartService, Request $request): JsonResponse
    {   
        if ($cartService->getItemQuantity($prodId) > 1){ 
            $cartService->subQuantity($prodId);
        }
        return new JsonResponse([
            'quantity' => $cartService->getItemQuantity($prodId), 
            'price' => $cartService->getItemTotalPrice($prodId),
            'totalPrice' => $cartService->getTotalPrice(),
            'subtotalPrice' => $cartService->getSubtotalPrice(),
            'shippingPrice' => $cartService->getShippingPrice(),
            'productStock' => $cartService->getProductStock($prodId)
        ]);
    }
}