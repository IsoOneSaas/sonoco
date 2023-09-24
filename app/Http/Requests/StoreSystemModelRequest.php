<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//use Log;
class StoreSystemModelRequest extends FormRequest
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
        return [
            'code'          => 'required|min:2|max:8|unique:set_systems,code,'.$this->system_id.',system_id',
            'name'          => 'required|min:2|max:64|unique:set_systems,name,'.$this->system_id.',system_id',
            'description'   => 'required|min:8|max:255',
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
             'name.required'         => trans('system.request.name.required'),
             'name.min'              => trans('system.request.name.min'),
             'name.max'              => trans('system.request.name.max'),
             'name.unique'           => trans('system.request.name.unique'),
             'code.required'         => trans('system.request.code.required'),
             'code.min'              => trans('system.request.code.min'),
             'code.max'              => trans('system.request.code.max'),
             'code.unique'           => trans('system.request.code.unique'),            
             'description.required'  => trans('system.request.description.required'),
             'description.min'       => trans('system.request.description.min'),
             'description.max'       => trans('system.request.description.max'),
         ];
     }    
} // class
