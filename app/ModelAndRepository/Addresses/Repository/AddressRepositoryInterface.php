<?php
namespace App\ModelAndRepository\Addresses\Repository;


use App\ModelAndRepository\Addresses\Address;

interface AddressRepositoryInterface{
    public function createAddress(array $params):Address;
    public function updateAddress(int $id,array $params):Address;
    public function deleteAddress(int $id):bool;

}