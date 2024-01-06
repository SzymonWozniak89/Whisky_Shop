<?php
namespace App\Service;

use App\Entity\Address;
use App\Entity\User;
use App\Repository\AddressRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;

class AddressService{

    private User $user;
    
    public function __construct(
        public readonly AddressRepository $addressRepository,
        public readonly UserRepository $userRepository,
        private readonly Security $security,
        ){
            $this->user = $security->getUser();
    }

    public function addNewAddress(Address $address) 
    {
        $address->setUser($this->user);
        return $this->addressRepository->save($address);
    }

    public function getUserAddresses()
    {
        return $this->addressRepository->findUserAddresses($this->user);
    }

    public function edit($address)
    {
        return $this->addressRepository->save($address);
    }

    public function delete($address)
    {
        return $this->addressRepository->delete($address);
    }

    public function setShippingAddress($id)
    {
        return $this->userRepository->setShippingAddress($id, $this->user);
    }

    public function getShippingAddress()
    {
        return $this->addressRepository->getShippingAddress($this->user);
    }
}