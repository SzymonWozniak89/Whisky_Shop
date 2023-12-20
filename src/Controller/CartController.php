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
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

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

    #[Route('/show', name: 'show', methods: ['GET'])]
    public function cart(CartItemRepository $cartItemRepository): Response
    {
        /**  @var User $user */
        $user = $this->getUser();
        return $this->render('cart/index.html.twig', [
            'cartItem' => $cartItemRepository->get($user),
        ]); 
    }

    #[Route('/remove/{id}', name: 'remove', methods: ['GET'])]
    public function remove(Request $request, CartItem $cartItem, CartService $cartService, CartItemRepository $cartItemRepository): Response
    {   
        if ($this->isCsrfTokenValid('delete'.$cartItem->getId(), $request->request->get('_token'))) {
            $cartService->remove($cartItem);
            $this->addFlash('success', 'Product removed');
        }
        /**  @var User $user */
        $user = $this->getUser(); 
        return $this->render('cart/index.html.twig', [
            'cartItem' => $cartItemRepository->get($user),
        ]);      
    }

    // #[Route('/car/delete/{id}', name: 'car_delete', methods: ['POST'])]
    // public function delete(Request $request, Car $car, CarService $carService): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$car->getCarId(), $request->request->get('_token'))) {
    //         $carService->deleteCar($car);
    //         $this->addFlash('success', 'Usunięto samochód!');
    //     }

    //     return $this->redirectToRoute('app_car');
    // }
}