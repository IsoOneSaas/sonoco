<?php namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory as ValidationFactory;

class StoreRecordModelRequest extends FormRequest
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
            'name'      => 'required|min:8|regex:'. config('settings.document_name_pattern'),
            'topic'     => 'required|min:2|regex:'. config('settings.document_name_pattern'),
            'subject'   => 'required|min:2|regex:'. config('settings.document_name_pattern'),

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
             'name.required'         => trans('document/record.request.name.required'),
             'name.min'              => trans('document/record.request.name.format'),
             'name.regex'           => trans('document/record.request.name.regex'),
             'topic.required'         => trans('document/record.request.topic.required'),
             'topic.min'              => trans('document/record.request.topic.format'),
             'topic.regex'           => trans('document/record.request.topic.regex'),             
             'subject.required'         => trans('document/record.request.subject.required'),
             'subject.min'              => trans('document/record.request.subject.format'),
             'subject.regex'           => trans('document/record.request.subject.regex'),  
         ];
     }    
} // class
