<?php

namespace App\Controller;

use App\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\AddressType;
use App\Service\AddressService;

#[Route('/address', name: 'address_', methods: ['GET','POST'])]
class AddressController extends AbstractController
{
    #[Route('/show', name: 'show', methods: ['GET', 'POST'])]
    public function address(AddressService $addressService): Response
    {
        return $this->render('address/index.html.twig', [
            'addresses' => $addressService->getUserAddresses(),
        ]); 
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function addAddress(AddressService $addressService, Request $request): Response
    {
        $address = new Address;
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $addressService->addNewAddress($address);
            $addressService->setShippingAddress($address->getId());
            $this->addFlash('success', 'Address added');
            return $this->redirectToRoute('checkout_show');
        }
        return $this->render('address/add.html.twig', [
            'addressAdd'=>$form->createView(),
        ]); 
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['POST','GET'])]

    public function editAddress(Address $address, Request $request, AddressService $addressService): Response
    {    
         $form = $this->createForm(AddressType::class, $address);
         $form->handleRequest($request);
            
         if ($form->isSubmitted() && $form->isValid()) 
         {
             $addressService->edit($address);

             $this->addFlash('success', 'Address updated');
             
             return $this->redirectToRoute('address_show');
         }

         return $this->render('address/edit.html.twig', [
             'addressEdit' => $form->createView()
         ]);      
     }

     #[Route('/delete/{id}', name: 'delete', methods: ['GET', 'POST'])]
     public function delete(Address $address, Request $request, AddressService $addressService): Response
     {
         if ($this->isCsrfTokenValid('delete'.$address->getId(), $request->request->get('_token'))) {
             $addressService->delete($address);
             $this->addFlash('success', 'Address deleted');
         }

         return $this->redirectToRoute('checkout_show');
     }

     #[Route('/set/{id}', name: 'set', methods: ['POST','GET'])]
     public function setAddress(int $id, AddressService $addressService): Response
     {                 
              $addressService->setShippingAddress($id);              
              return $this->redirectToRoute('checkout_show');
     }

}