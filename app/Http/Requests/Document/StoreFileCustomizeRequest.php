<?php namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;
//use Log;
class StoreFileCustomizeRequest extends FormRequest
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
            'file_code_pad'          => 'required',
            'record_nui_pad'          => 'required',
            'record_nui_format'       => 'required',
            'file_code_format'       => 'required',
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
             'file_code_pad.required'         => trans('document/customize.request.file_code_pad.required'),
             'record_nui_pad.required'         => trans('document/customize.request.record_nui_pad.required'),
             'record_nui_format.required'      => trans('document/customize.request.record_nui_format.required'),
             'file_code_format.required'      => trans('document/customize.request.file_code_format.required'),
         ];
     }    
} // class
