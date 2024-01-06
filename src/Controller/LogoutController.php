<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\Annotation\Route;
class LogoutController extends AbstractController
{

#[Route('/logout', name: 'logout')]
    public function logout(Security $security)
    {
        $security->logout();
        $this->addFlash('success', 'Logged out');
    }
}