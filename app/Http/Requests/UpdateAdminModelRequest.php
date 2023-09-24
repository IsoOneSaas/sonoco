<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//use Log;
class UpdateAdminModelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        //Log::debug(['USERS REQUEST' => $this->all()]);
        return [
            'location_id'        => 'required|array|min:1',
            'system_id'          => 'required|array|min:1',
        ];
    }

   /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
         return [
             'location_id.required'   => trans('admin.request.location_id.required'),
             'location_id.min'        => trans('admin.request.location_id.min'),
             'location_id.array'      => trans('admin.request.location_id.array'),
             'system_id.required'   => trans('admin.request.system_id.required'),
             'system_id.min'        => trans('admin.request.system_id.min'),
             'system_id.array'      => trans('admin.request.system_id.array'),                                      
         ];
     }    
} // class
