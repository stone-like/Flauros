<?php
namespace App\ModelAndRepository\Products\Requests;


use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name"=>["required",
            Rule::unique('products')->ignore((int)$this->id)],
            "images.*.photo"=>["sometimes","image"],
            "quantity"=>["required"],
            "price"=>["required"],
            "category_id"=>["required","exists:categories,id"]      
        ];
    }
}
