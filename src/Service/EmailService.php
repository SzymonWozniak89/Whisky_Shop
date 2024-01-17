<?php
namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\OrderService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class EmailService{

    private User $user;

    public function __construct(
        public readonly Security $security,
        public readonly UserRepository $userRepository,
        public readonly MailerInterface $mailer,
        public readonly OrderService $orderService,
        ){

            $this->user = $security->getUser();
    }

    public function orderConfirmation($order)
    {
        $email = (new TemplatedEmail())
            ->from('order@whiskyshop.com')
            ->to($this->user->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Order confirmation')
            ->htmlTemplate('order/email.html.twig')
            //->text('TEST');
            ->context([
                'orderItem' => $this->orderService->getOrderItems($order),
                'totalPrice' => $order->getAmount(),
                'netAmount' => $order->getNetAmount(),
                'vatAmount' => $order->getVatAmount(),
                'shippingPrice' => $order->getShipmentPrice(),
            ]);
        try{
            $this->mailer->send($email);
        } catch(TransportExceptionInterface $e) {
            dd($e->getMessage());
        }
    }
}