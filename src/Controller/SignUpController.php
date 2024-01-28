<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\SignUpType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class SignUpController extends AbstractController
{
    #[Route('/signUp', name: 'signUp', methods: ['POST', 'GET'])]
    public function register(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher)
    {
        $user = new User();
        $form = $this->createForm(SignUpType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {   
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );

            $user = $form->getData();
            $userRepository->saveUser($user);

            return $this->redirectToRoute('login');
        }

        return $this->render('signUp/index.html.twig', [
            'register'=>$form->createView()
        ]);      
    }
}