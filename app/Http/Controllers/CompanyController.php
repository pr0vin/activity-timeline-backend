<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //s
        return Company::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        //

        $data = $request->validated();

        $company = Company::create(array_merge($data, [
            'user_id' => Auth::user()->id,
        ]));


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
        //
        return $company;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        //
        $data = $request->validated();
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
        $company->delete();

        return response()->json([
            'message' => 'Successfully Deleted'
        ]);
    }
}
