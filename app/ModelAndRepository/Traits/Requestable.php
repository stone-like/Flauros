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
       //なぜかfilledで空扱いされてしまう・・・？
       if($request->has("image") && $request->image instanceOf UploadedFile){
           $image = $request->image->store($storeTo,["disk" => "public"]);
       }

       $request->merge([
           "slug" => $slug,
           "image" => $image
       ]);

       if($storeTo === "products"){
           $request->merge([
               "status" => Product::getStatus($request->quantity)
           ]);
       }
      
       return $request->all();
   }
}