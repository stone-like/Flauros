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
use App\ModelAndRepository\Traits\Requestable;



class Category extends Model
{
    use NodeTrait,Requestable;

    protected $fillable  = [
        "name",
        "slug",
        "image",
        "parent_id"
    ];

    public function products(){
        return $this->belongsToMany(Product::class);
    }
    
}
