<?php
namespace App\Service;

use App\Entity\Address;
use App\Entity\User;
use App\Repository\AddressRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;

class AddressService{
    public function __construct(
        public readonly AddressRepository $addressRepository,
        public readonly UserRepository $userRepository,
        private readonly Security $security,
        ){

    }

    public function addNewAddress(Address $address) 
    {
        /** @var User $user */
        $user=$this->security->getUser();
        $address->setUser($user);
        return $this->addressRepository->save($address);
    }

    public function getUserAddresses()
    {
        /** @var User $user */
        $user=$this->security->getUser();
        return $this->addressRepository->findUserAddresses($user);
    }

    public function edit($address)
    {
        return $this->addressRepository->save($address);
    }

    public function delete($address)
    {
        return $this->addressRepository->delete($address);
    }
}