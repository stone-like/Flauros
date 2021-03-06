<?php
namespace App\ModelAndRepository\Products\Requests;


use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
        
        //一応権限与えた人しかいじれないのでspam対策はいらないか？
        return [
            "name"=>["required",
        "unique:products,name"],
            "quantity"=>["required"],
            "price"=>["required"],
            'images.*'=>["sometimes","image"],
            "category_id"=>["required","exists:categories,id"]//後で追加もありにしようかと思ったけど多分しないと思ったのでrequire       
        ];
    }
}
