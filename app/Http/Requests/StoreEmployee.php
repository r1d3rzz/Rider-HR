<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployee extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('employees');
        return [
            "employee_id" => "required|unique:users,employee_id",
            "name" => 'required|min:3',
            "email" => 'required|email|unique:users,email',
            "phone" => 'required|unique:users,phone',
            "nrc_number" => 'required|unique:users,nrc_number',
            "gender" => 'required',
            "birthday" => 'required',
            "date_of_join" => 'required',
            "address" => 'required',
            "department_id" => 'required|exists:departments,id',
            "is_present" => 'required',
            "password" => 'required|min:8',
            "pin_code" => 'required|min:6|max:6|unique:users,pin_code,' . $id,
        ];
    }
}
