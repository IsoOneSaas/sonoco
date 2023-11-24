<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//use Log;
class StoreProcessModelRequest extends FormRequest
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
            'code'          => 'required|min:2|max:8|unique:set_processes,code,'.$this->process_id.',process_id',
            'name'          => 'required|min:2|max:64|unique:set_processes,name,'.$this->process_id.',process_id',
            'version'       => 'required|max:24',
            'target'        => 'required|min:8',
            'job_id'        => 'required',
            'department_id' => 'required|array|min:1',            
            //'auth_id'       => 'required|array|min:1', 
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
            'name.required'         => trans('process.request.name.required'),
            'name.min'              => trans('process.request.name.min'),
            'name.max'              => trans('process.request.name.max'),
            'name.unique'           => trans('process.request.name.unique'),

            'code.required'         => trans('process.request.code.required'),
            'code.min'              => trans('process.request.code.min'),
            'code.max'              => trans('process.request.code.max'),
            'code.unique'           => trans('process.request.code.unique'),
            
            'version.required'      => trans('process.request.version.required'),
            'description.max'       => trans('process.request.description.max'),
            
            'target.required'       => trans('process.request.target.required'),
            'target.min'            => trans('process.request.target.min'),

            'job_id.required'         => trans('process.request.job_id.required'),
            
            'department_id.required'   => trans('process.request.department_id.required'),
            'department_id.min'        => trans('process.request.department_id.min'),
            'department_id.array'      => trans('process.request.department_id.array'), 
            
            'auth_id.required'   => trans('process.request.auth_id.required'),
            'auth_id.min'        => trans('process.request.auth_id.min'),
            'auth_id.array'      => trans('process.request.auth_id.array'),             
    
         ];
     }    
} // class
