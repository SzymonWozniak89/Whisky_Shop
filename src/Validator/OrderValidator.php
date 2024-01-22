<?php
namespace App\Validator;


use App\Entity\Order;
use Exception;

class OrderValidator{

    public function __construct(){}

    public function validate(Order $order)
    {
        if (($order->getAmount()) != ($order->getNetAmount()+$order->getVatAmount()))
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