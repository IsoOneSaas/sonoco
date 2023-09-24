<?php namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class StoreTemplateModelRequest extends FormRequest
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
            'name'          => 'required|min:2|max:64|unique:document_templates,name,'.$this->template_id.',template_id',
            'description'   => 'required|min:5|max:255',
            'content'       => 'required',
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
             'name.required'         => trans('document/template.request.name.required'),
             'name.min'              => trans('document/template.request.name.min'),
             'name.max'              => trans('document/template.request.name.max'),
             'name.unique'           => trans('document/template.request.name.unique'),
             'description.required'  => trans('document/template.request.description.required'),
             'description.min'       => trans('document/template.request.description.min'),
             'description.max'       => trans('document/template.request.description.max'),
             'content.required'      => trans('document/template.request.content.required'),        
         ];
     }    
} // class
