<?php

namespace App\ModelAndRepository\Categories;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\CreateCategoryRequest;
use App\ModelAndRepository\Products\Product;

class Category extends Model
{
    use NodeTrait;

    protected $fillable  = [
        "name",
        "slug",
        "image",
        "parent_id"
    ];

    public function products(){
        return $this->belongsToMany(Product::class);
    }

    public static function makeRequest(Request $request):array{
          //ここでrequestを新しくしているのはmergeがうまくいかないため、ここでただ持ってきたrequestとimageをmergeしてしまうとimageの詳細なデータまでmergeしてしまう
          $request = new Request($request->all());

          $slug = Str::slug($request->name);
          //なぜかfilledで空扱いされてしまう・・・？
          if($request->has("image") && $request->image instanceOf UploadedFile){
              $image = $request->image->store("categories",["disk" => "public"]);
          }

          $request->merge([
              "slug" => $slug,
              "image" => $image
          ]);

         
          
          return $request->all();
    }
    
}
