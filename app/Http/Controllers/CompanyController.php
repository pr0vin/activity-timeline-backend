<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //s
        return Company::with('users')->whereNot('id', 1)->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        $data = $request->validated();

        if ($request->file('logo')) {
            $data['logo'] = Storage::putFile('company-logo', $request->file('logo'));
        }

        if ($request->file('subLogo')) {

            $data['subLogo'] = Storage::putFile('company-subLogo', $request->file('subLogo'));
        }
        $company = Company::create($data);
        return response()->json([
            'company' => $company,
            'message' => 'Successfully created'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {

        return $company;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        //
        $data = $request->validated();

        if ($request->file('logo')) {
            if ($company->logo) {
                Storage::delete($company->logo);
            }
            $data['logo'] = Storage::putFile('company-logo', $request->file('logo'));
        }

        if ($request->file('subLogo')) {
            if ($company->subLogo) {
                Storage::delete($company->subLogo);
            }

            $data['subLogo'] = Storage::putFile('company-subLogo', $request->file('subLogo'));
        }

        $company->update($data);




        return response()->json([
            'message' => 'Successfully updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //

        if ($company->logo) {
            Storage::delete($company->logo);
        }
        if ($company->subLogo) {
            Storage::delete($company->subLogo);
        }
        $company->delete();

        return response()->json([
            'message' => 'Successfully Deleted'
        ]);
    }

    public function user()
    {


        $user = User::with('roles', 'company')->findOrfail(Auth::user()->id);


        return response()->json(
            [
                'status' => true,
                'user' => $user,
            ],
            200,
        );
    }
}
