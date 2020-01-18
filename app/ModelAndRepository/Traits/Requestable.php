<?php
namespace App\ModelAndRepository\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\ModelAndRepository\Products\Product;

trait Requestable{
   public static function makeRequest(Request $request,string $storeTo):array{
       //ここでrequestを新しくしているのはmergeがうまくいかないため、ここでただ持ってきたrequestとimageをmergeしてしまうとimageの詳細なデータまでmergeしてしまう
       $request = new Request($request->all());

       $slug = Str::slug($request->name);
       $request->merge([
        "slug" => $slug
    ]);
         if($storeTo === "products"){
           $request->merge([
               "status" => Product::getStatus($request->quantity)
           ]);
           //productsの場合imagesだけ除きたい
           return $request->except("images");
       }
      
       return $request->all();
   }
}