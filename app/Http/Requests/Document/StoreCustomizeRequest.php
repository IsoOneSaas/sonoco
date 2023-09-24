<?php namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;
//use Log;
class StoreCustomizeRequest extends FormRequest
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
            'code_format'          => 'required',
            'date_format'          => 'required',
            'from_name_edit'       => 'required',
            'from_email_edit'       => 'required',
            'subject_edit'         => 'required',
            'signature_edit'       => 'required',
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
             'code_format.required'         => trans('document/customize.request.code_format.required'),
             'date_format.required'         => trans('document/customize.request.date_format.required'),
             'from_name_edit.required'      => trans('document/customize.request.from_name_edit.required'),
             'from_name_email.required'      => trans('document/customize.request.from_email_edit.required'),
             'subject_edit.required'      => trans('document/customize.request.subject_edit.required'),
             'signature_edit.required'      => trans('document/customize.request.signature_edit.required'),
         ];
     }    
} // class
