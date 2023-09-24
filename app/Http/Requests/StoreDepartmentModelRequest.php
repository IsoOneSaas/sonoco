<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//use Log;
class StoreDepartmentModelRequest extends FormRequest
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
            'code'          => 'required|min:2|max:8|unique:set_departments,code,'.$this->department_id.',department_id',
            'name'          => 'required|min:2|max:64|unique:set_departments,name,'.$this->department_id.',department_id',
            'description'   => 'required|min:8|max:255',
            'locations'     => 'required|array|min:1'
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
             'name.required'         => trans('department.request.name.required'),
             'name.min'              => trans('department.request.name.min'),
             'name.max'              => trans('department.request.name.max'),
             'name.unique'           => trans('department.request.name.unique'),
             'code.required'         => trans('department.request.code.required'),
             'code.min'              => trans('department.request.code.min'),
             'code.max'              => trans('department.request.code.max'),
             'code.unique'           => trans('department.request.code.unique'),            
             'description.required'  => trans('department.request.description.required'),
             'description.min'       => trans('department.request.description.min'),
             'description.max'       => trans('department.request.description.max'),
             'locations.required'   => trans('department.request.locations.required'),
             'locations.min'        => trans('department.request.locations.min'),
             'locations.array'      => trans('department.request.locations.array'),             
         ];
     }    
} // class
