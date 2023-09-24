<?php namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class StoreTypeModelRequest extends FormRequest
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
        //Log::debug(['DEPARTMENT REQUEST' => $this->all()]);
        return [
            'code'          => 'required|min:2|max:8|unique:document_types,code,'.$this->type_id.',type_id',
            'name'          => 'required|min:2|max:64|unique:document_types,name,'.$this->type_id.',type_id',
            'category'      => 'required|min:1',
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
             'name.required'         => trans('document/type.request.name.required'),
             'name.min'              => trans('document/type.request.name.min'),
             'name.max'              => trans('document/type.request.name.max'),
             'name.unique'           => trans('document/type.request.name.unique'),
             'code.required'         => trans('document/type.request.code.required'),
             'code.min'              => trans('document/type.request.code.min'),
             'code.max'              => trans('document/type.request.code.max'),
             'code.unique'           => trans('document/type.request.code.unique'),            
             'category.required'    => trans('document/type.request.category.required'),
             'category.min'         => trans('document/type.request.category.min'),           
         ];
     }    
} // class
