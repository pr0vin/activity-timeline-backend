<?php

namespace App\Http\Controllers;

use App\Models\FiscalYear;
use App\Http\Requests\StoreFiscalYearRequest;
use App\Http\Requests\UpdateFiscalYearRequest;
use Illuminate\Auth\Events\Validated;

class FiscalYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fiscalyears = FiscalYear::latest()->get();

        return $fiscalyears;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFiscalYearRequest $request)
    {
        //
        $data = $request->validated();

        $fiscalyear = FiscalYear::create($data);

        return response()->json([
            'message' => "Successfully created"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(FiscalYear $fiscalYear)
    {
        //
        return $fiscalYear;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFiscalYearRequest $request, FiscalYear $fiscalYear)
    {
        //

        $data = $request->Validated();

        $fiscalYear->update($data);
        return response()->json([
            'message' => "Successfully updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FiscalYear $fiscalYear)
    {
        //

        $fiscalYear->delete();
        return response()->json([
            'message' => "Successfully deleted"
        ]);
    }
}
