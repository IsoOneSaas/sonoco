<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//use Log;
class StoreUserModelRequest extends FormRequest
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
            'name'          => 'required|min:8',
            'email'         => 'required|email|unique:set_users,email,'.$this->user_id.',user_id',
            'password'      => 'nullable|regex:/^(?=.{8,32}$)(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).*/',
            'role'          => 'required|min:1',
            'job_id'        => 'required|min:1',
            'location_id'   => 'required|min:1',
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
             'name.required'         => trans('user.request.name.required'),
             'name.min'              => trans('user.request.name.min'),
             'email.required'        => trans('user.request.email.required'),
             'email.email'           => trans('user.request.email.min'),
             'email.unique'          => trans('user.request.email.unique'),            
             'password.required'     => trans('user.request.password.required'),
             'password.regex'        => trans('user.request.password.regex'),
             'job_id.required'   => trans('user.request.job_id.required'),
             'job_id.min'        => trans('user.request.job_id.min'),
             'job_id.array'      => trans('user.request.job_id.array'),
             'location_id.required'   => trans('user.request.location_id.required'),
             'location_id.min'        => trans('user.request.location_id.min'),
             'location_id.array'      => trans('user.request.location_id.array'),             
             'role.required'   => trans('user.request.role.required'),
             'role.min'        => trans('user.request.role.min'),                          
         ];
     }    
} // class
