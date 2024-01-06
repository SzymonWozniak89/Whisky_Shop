<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Shipment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CartService;
use App\Service\CartCalculatorService;
use App\Service\ShipmentService;
use Symfony\Component\HttpFoundation\Request;

#[Route('/cart', name: 'cart_', methods: ['GET','POST'])]
class CartController extends AbstractController
{

    #[Route('/add/{prodId}', name: 'add', methods: ['GET'])]
    public function add(int $prodId, CartService $cartService, ShipmentService $shipmentService, CartCalculatorService $cartCalculatorService, Request $request): Response
    {   
        if ($request->isXmlHttpRequest()) {  
            if ($cartService->getItemQuantity($prodId) < $cartService->getProductStock($prodId)){
                $cartService->add($prodId);
            } 
            return new JsonResponse([
                'quantity' => $cartService->getItemQuantity($prodId), 
                'price' => $cartService->getItemTotalPrice($prodId),
                'totalPrice' => $cartCalculatorService->getCartTotalPrice(),
                'subtotalPrice' => $cartService->getSubtotalPrice(),
                'cheapestShipping' => $shipmentService->getCheapestShipping(),
                'productStock' => $cartService->getProductStock($prodId)
            ]);

        } else {
            $cartService->add($prodId);  
            $this->addFlash('success', 'Product added'); 
            return $this->redirectToRoute('product');    
        } 

    }

    #[Route('/show', name: 'show', methods: ['GET'])]
    public function cart(CartService $cartService, ShipmentService $shipmentService, CartCalculatorService $cartCalculatorService): Response
    {
        return $this->render('cart/index.html.twig', [
            'cartItem' => $cartService->getCartItems(),
            'totalPrice' => $cartCalculatorService->getCartTotalPrice(),
            'subtotalPrice' => $cartService->getSubtotalPrice(),
            'cheapestShipping' => $shipmentService->getCheapestShipping(),
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
    public function sub(int $prodId, CartService $cartService, ShipmentService $shipmentService, CartCalculatorService $cartCalculatorService): JsonResponse
    {   
        if ($cartService->getItemQuantity($prodId) > 1){ 
            $cartService->subQuantity($prodId);
        }
        return new JsonResponse([
            'quantity' => $cartService->getItemQuantity($prodId), 
            'price' => $cartService->getItemTotalPrice($prodId),
            'totalPrice' => $cartCalculatorService->getCartTotalPrice(),
            'subtotalPrice' => $cartService->getSubtotalPrice(),
            'shippingPrice' => $cartService->getShippingPrice(),
            'productStock' => $cartService->getProductStock($prodId),
            'cheapestShipping' => $shipmentService->getCheapestShipping(),
        ]);
    }
}