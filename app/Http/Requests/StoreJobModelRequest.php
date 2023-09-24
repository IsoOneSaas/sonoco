<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//use Log;
class StoreJobModelRequest extends FormRequest
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
            'name'          => 'required|min:2|max:64|unique:set_jobs,name,'.$this->job_id.',job_id',
            'description'   => 'required|min:8|max:255',
            'department_id' => 'required|min:1'
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
             'name.required'         => trans('job.request.name.required'),
             'name.min'              => trans('job.request.name.min'),
             'name.max'              => trans('job.request.name.max'),
             'name.unique'           => trans('job.request.name.unique'),          
             'description.required'  => trans('job.request.description.required'),
             'description.min'       => trans('job.request.description.min'),
             'description.max'       => trans('job.request.description.max'),
             'department_id.required'   => trans('job.request.department_id.required'),
             'department_id.min'        => trans('job.request.department_id.min'),           
         ];
     }    
} // class
