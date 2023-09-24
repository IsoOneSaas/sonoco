<?php namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;
//use Log;
class StoreSuggestionRequest extends FormRequest
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
            'system_id'     => 'required',
            'document'      => 'required|min:2|max:255',
            'justification' => 'required|min:8',
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
             'document.required'         => trans('suggestion.request.document.required'),
             'document.min'              => trans('suggestion.request.document.valid'),
             'document.max'              => trans('suggestion.request.document.valid'),
             'system_id.required'        => trans('suggestion.request.system.required'),           
             'justification.required'  => trans('suggestion.request.justification.required'),
             'justification.min'       => trans('suggestion.request.justification.valid'),
         ];
     }    
} // class
