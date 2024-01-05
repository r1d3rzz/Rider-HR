<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EmployeeProfileController extends Controller
{
    public function show($id)
    {
        if (!User::findOrFail(auth()->id())->can('view_profile')) {
            return abort(401);
        }

        return view('employee.profile', [
            'user' => User::findOrFail($id),
        ]);
    }

    public function edit($id)
    {
        if (!User::findOrFail(auth()->id())->can('edit_profile')) {
            return abort(401);
        }

        $employee = User::findOrFail($id);

        return view('employee.edit_profile', [
            'employee' => $employee,
        ]);
    }

    public function update($id, UpdateProfile $request)
    {
        if (!User::findOrFail(auth()->id())->can('edit_profile')) {
            return abort(401);
        }

        request()->validate([
            "email" => Rule::unique('users', 'email')->ignore($id),
            "nrc_number" => Rule::unique('users', 'nrc_number')->ignore($id),
            "phone" => Rule::unique('users', 'phone')->ignore($id),
        ]);

        $employee = User::findOrFail($id);
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->nrc_number = $request->nrc_number;
        $employee->gender = $request->gender;
        $employee->birthday = $request->birthday;
        $employee->address = $request->address;

        if ($request->password) {
            if (strlen($request->password) >= 8) {
                $employee->password = $request->password;
            } else {
                return back()->withErrors(["password" => "The password field must be at least 8 characters."]);
            }
        } else {
            $employee->password;
        }

        if (isset($request->deleteAvatar) && $request->deleteAvatar == 'on') {
            $employee->avatar = null;
        } else {
            $request->file('avatar') ? $employee->avatar = $request->file('avatar')->store('Employee') : $employee->avatar;
        }

        $employee->update();

        if (auth()->id() == $id && $request->password) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/');
        }

        return redirect(route('employee_profile.show', auth()->id()))->with('updated', 'Employee Updated Successful');
    }

    public function destroy($id)
    {
        if (!User::findOrFail(auth()->id())->can('remove_profile')) {
            return abort(401);
        }

        $employee = User::findOrFail($id);
        $employee->delete();

        return "success";
    }
}
