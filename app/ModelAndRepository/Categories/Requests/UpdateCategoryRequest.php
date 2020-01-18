<?php
namespace App\ModelAndRepository\Categories\Requests;



use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
        //inputはmergeしたやつは加わらない、きちんと持ってきた奴だけ
         //updateするときは一旦createしたやつから変えて全てを送るはずなのでnameは絶対入っている
         //そのときにuniqueが自分以外の条件を付けておかないと、すでにある自分のと競合してしまう
         //ignore($this->input("id"))とすることでこのrecordだけは無視してくれる
        return [ 
            "name"=>["required",
        "unique:categories,name",
        Rule::unique('categories')->ignore((int)$this->id)],
            "parent_id"=>["sometimes",
           "exists:categories,id"]
        ];
    }
}
