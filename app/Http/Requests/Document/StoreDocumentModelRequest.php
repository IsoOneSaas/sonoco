<?php namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory as ValidationFactory;
use Log;
//use Log;
class StoreDocumentModelRequest extends FormRequest
{

    public function __construct(ValidationFactory $validationFactory)
    {

        $validationFactory->extend(
            'single_word', 
            function ($attribute, $value, $parameters, $validator) {
                return is_string($value) && ! preg_match('/\s/u', $value);
        });
        
        $validationFactory->extend(
            'key_words', 
            function ($attribute, $value, $parameters, $validator) {
                $string = trim($value);
                Log::debug(['REQUEST $string=' => $string]);
                // if son más de una palabra 
                if(count(explode(' ', $string)) > 0) {
                    Log::debug(['REQUEST Words =' => count(explode(' ', $string))]);
                    if(count(explode(',', $string)) > 0) {
                        Log::debug(['REQUEST Commas =' => count(explode(',', $string))]);
                        return true;
                    }
                    return false;
                } 
                return true;
        });         

    }    

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
            //'code'          => 'required|min:2|unique:documents,code,'.$this->document_id.',document_id',
            'code'          => 'required|min:2',
            'name'          => 'required|min:8|regex:'. config('settings.document_name_pattern'),
            'version'       => 'required|integer|min:1',
            'system_id'     => 'required|integer|min:1',
            'location_id'     => 'required|integer|min:1',
            'department_id'     => 'required|integer|min:1',
            'process_id'     => 'required|integer|min:1',
            'type_id'           => 'required|integer|min:1',

            'job_edit_id'       => 'required|array|min:1',
            'job_review_id'     => 'required|array|min:1',
            'job_approve_id'    => 'required|array|min:1',

            'user_edit_id'       => 'required|array|min:1',
            'user_review_id'     => 'required|array|min:1',
            'user_approve_id'    => 'required|array|min:1',

            'class'             => 'nullable|single_word', 
           //'tags'              => 'required_if:class,!=,""|key_words',
           'tags'              => 'required_with:class|min:2|key_words'

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
             'name.required'         => trans('document/document.request.name.required'),
             'name.min'              => trans('document/document.request.name.format'),
             'name.regex'           => trans('document/document.request.name.format'),
             'code.required'         => trans('document/document.request.code.format'),
             'code.min'              => trans('document/document.request.code.format'),           
             'version.required'              => trans('document/document.request.version.format'), 
             'version.integer'              => trans('document/document.request.version.format'),
             'system_id.required'   => trans('document/document.request.system_id.format'),
             'system_id.min'        => trans('document/document.request.system_id.format'),
             'system_id.integer'      => trans('document/document.request.system_id.format'),             
             'location_id.required'   => trans('document/document.request.location_id.format'),
             'location_id.min'        => trans('document/document.request.location_id.format'),
             'location_id.integer'      => trans('document/document.request.location_id.format'),
             'department_id.required'   => trans('document/document.request.department_id.format'),
             'department_id.min'        => trans('document/document.request.department_id.format'),
             'department_id.integer'      => trans('document/document.request.department_id.format'), 
             'process_id.required'   => trans('document/document.request.process_id.format'),
             'process_id.min'        => trans('document/document.request.process_id.format'),
             'process_id.integer'      => trans('document/document.request.process_id.format'),
             'type_id.required'   => trans('document/document.request.type_id.format'),
             'type_id.min'        => trans('document/document.request.type_id.format'),
             'type_id.integer'      => trans('document/document.request.type_id.format'),
             
             'job_edit_id.required'   => trans('document/document.request.job_edit_id.format'),
             'job_edit_id.min'        => trans('document/document.request.job_edit_id.format'),
             'job_edit_id.array'      => trans('document/document.request.job_edit_id.format'), 

             'job_review_id.required'   => trans('document/document.request.job_review_id.format'),
             'job_review_id.min'        => trans('document/document.request.job_review_id.format'),
             'job_review_id.integer'      => trans('document/document.request.job_review_id.format'), 
             
             'job_approve_id.required'   => trans('document/document.request.job_approve_id.format'),
             'job_approve_id.min'        => trans('document/document.request.job_approve_id.format'),
             'job_approve_id.integer'      => trans('document/document.request.job_approve_id.format'),              
             
             'user_edit_id.required'   => trans('document/document.request.user_edit_id.format'),
             'user_edit_id.min'        => trans('document/document.request.user_edit_id.format'),
             'user_edit_id.array'      => trans('document/document.request.user_edit_id.format'), 

             'user_review_id.required'   => trans('document/document.request.user_review_id.format'),
             'user_review_id.min'        => trans('document/document.request.user_review_id.format'),
             'user_review_id.integer'      => trans('document/document.request.user_review_id.format'), 
             
             'user_approve_id.required'   => trans('document/document.request.user_approve_id.format'),
             'user_approve_id.min'        => trans('document/document.request.user_approve_id.format'),
             'user_approve_id.integer'      => trans('document/document.request.user_approve_id.format'),               

             'user_approve_id.integer'      => trans('document/document.request.user_approve_id.format'),

             'tags.required_with'         => trans('document/document.request.tags.required_with'),
             'tags.min'                 => trans('document/document.request.tags.required_with'),
             'tags.key_words'         => trans('document/document.request.tags.key_words'),

             'class.single_word'        => trans('document/document.request.category.format'),
             //'class.single_word'        => 'La categoría debe ser una sola palabra simple', // SÓLO funciona así
         ];
     }    
} // class
