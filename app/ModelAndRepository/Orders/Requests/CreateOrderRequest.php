<?php
namespace App\ModelAndRepository\Orders\Requests;



use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
            "cartlist" =>["required"],
            "address_id"=>["required","exists:addresses,id"],
            "order_status_id"=>["required","exists:order_statuses,id"],
            "tracking_number"=>["required"]
        ];
    }
}
