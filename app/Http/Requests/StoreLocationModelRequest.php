<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//use Log;
class StoreLocationModelRequest extends FormRequest
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
        //Log::debug(['LOCATION REQUEST' => $this->all()]);
        return [
            'code'          => 'required|min:2|max:8|unique:set_locations,code,'.$this->location_id.',location_id',
            'name'          => 'required|min:2|max:64|unique:set_locations,name,'.$this->location_id.',location_id',
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
             'name.required'         => trans('location.request.name.required'),
             'name.min'              => trans('location.request.name.min'),
             'name.max'              => trans('location.request.name.max'),
             'name.unique'           => trans('location.request.name.unique'),
             'code.required'         => trans('location.request.code.required'),
             'code.min'              => trans('location.request.code.min'),
             'code.max'              => trans('location.request.code.max'),
             'code.unique'           => trans('location.request.code.unique'),            
             'description.required'  => trans('location.request.description.required'),
             'description.min'       => trans('location.request.description.min'),
             'description.max'       => trans('location.request.description.max'),
         ];
     }    
} // class
