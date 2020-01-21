<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ModelAndRepository\Addresses\Address;
use App\ModelAndRepository\Addresses\Requests\CreateAddressRequest;
use App\ModelAndRepository\Addresses\Requests\UpdateAddressRequest;
use App\ModelAndRepository\Addresses\Repository\AddressRepositoryInterface;
use PhpParser\Node\Expr\Cast\Bool_;

class AddressController extends Controller
{
    private $addressRepo;

    public function __construct(AddressRepositoryInterface $addressRepo)
    {
        $this->addressRepo = $addressRepo;
    }
    public function createAddress(CreateAddressRequest $request):Address{
         

        return $this->addressRepo->createAddress($request->all());
    }
    public function updateAddress(int $id,UpdateAddressRequest $request):Address{
        

        return $this->addressRepo->updateAddress($id,$request->all());
    }
    public function deleteAddress(int $id):bool{
        
         
        return $this->addressRepo->deleteAddress($id);
        
    
    }
}
