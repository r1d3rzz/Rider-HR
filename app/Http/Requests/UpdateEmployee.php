<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployee extends FormRequest
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
        return [
            "employee_id" => "required",
            "name" => 'required|min:3',
            "email" => 'required|email',
            "phone" => 'required',
            "nrc_number" => 'required',
            "gender" => 'required',
            "birthday" => 'required',
            "date_of_join" => 'required',
            "address" => 'required',
            "department_id" => 'required|exists:departments,id',
            "is_present" => 'required',
        ];
    }
}
