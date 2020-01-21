<?php
namespace App\ModelAndRepository\Addresses\Repository;

use App\Exceptions\AddressNotFoundException;
use App\ModelAndRepository\Addresses\Address;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class AddressRepository implements AddressRepositoryInterface{
    public function createAddress(array $params):Address
    {
       $address = auth()->user()->addresses()->create($params);
       return $address;
       
    
    }
    public function updateAddress(int $id,array $params):Address
    {
       $address = $this->findAddressById($id);
       $address->update($params);
       return $address;
    
    }
    public function deleteAddress(int $id): bool
    {
        $address = $this->findAddressById($id);
        
       return $address->delete();    
    }
    public function findAddressById(int $id):Address{
        try {
            return Address::where("id",$id)->firstOrFail();
        }catch(ModelNotFoundException $e){
            throw new AddressNotFoundException($e->getMessage());
        }
    }
}