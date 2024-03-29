<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfile extends FormRequest
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
        $id = $this->route('employee_profile');
        return [
            "name" => 'required|min:3',
            "email" => 'required|email|unique:users,email,' . $id,
            "phone" => 'required|unique:users,phone,' . $id,
            "nrc_number" => 'required|unique:users,nrc_number,' . $id,
            "gender" => 'required',
            "birthday" => 'required',
            "address" => 'required',
        ];
    }
}
