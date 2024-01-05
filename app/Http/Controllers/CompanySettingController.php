<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCompanySetting;
use App\Models\CompanySetting;
use App\Models\User;
use Illuminate\Http\Request;

class CompanySettingController extends Controller
{
    public function show($id)
    {
        if (!User::findOrFail(auth()->id())->can('view_company_setting')) {
            return abort(401);
        }

        $company = CompanySetting::findOrFail($id);
        return view('company_setting.show', ['company' => $company]);
    }

    public function edit($id)
    {
        if (!User::findOrFail(auth()->id())->can('edit_company_setting')) {
            return abort(401);
        }

        $company = CompanySetting::findOrFail($id);
        return view('company_setting.edit', ['company' => $company]);
    }

    public function update($id, UpdateCompanySetting $request)
    {
        if (!User::findOrFail(auth()->id())->can('edit_company_setting')) {
            return abort(401);
        }

        $company = CompanySetting::findOrFail($id);
        $company->name = $request->name;
        $company->email = $request->email;
        $company->phone = $request->phone;
        $company->address = $request->address;
        $company->office_start_time = $request->office_start_time;
        $company->office_end_time = $request->office_end_time;
        $company->break_start_time = $request->break_start_time;
        $company->break_end_time = $request->break_end_time;
        $company->update();

        return redirect(route('company_settings.show', 1))->with('updated', 'Company Setting is Updated');
    }
}
