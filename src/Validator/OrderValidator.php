<?php
namespace App\Validator;


use App\Entity\Order;
use Exception;
use function bccomp, bcadd;

class OrderValidator{

    public function __construct(){}

    public function validate(Order $order)
    {
        if (bccomp($order->getAmount(), bcadd($order->getNetAmount(), $order->getVatAmount(),2),2) != 0 )
        {
            throw new Exception('NET + VAT != TotalPrice!');
        }

        if ($order->getStatus() != 'completed')
        {
            throw new Exception('Order is not completed!');
        }

        if ($order->getAddress() == null)
        {
            throw new Exception('Delivery address is not filled!');
        }

        if ($order->getShipmentPrice() == null)
        {
            throw new Exception('Delivery method is not selected!');
        }

        if ($order->getPayment() == null)
        {
            throw new Exception('Payment method is not selected!');
        }

    }


}