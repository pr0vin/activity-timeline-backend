<?php

namespace App\Http\Controllers;

use App\Models\FiscalYear;
use App\Http\Requests\StoreFiscalYearRequest;
use App\Http\Requests\UpdateFiscalYearRequest;
use App\Http\Resources\FiscalYearResource;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class FiscalYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fiscalyears = FiscalYear::orderBy('order')->get();

        return FiscalYearResource::collection($fiscalyears);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFiscalYearRequest $request)
    {
        //
        $data = $request->validated();

        // return $data;
        if ($data['status'] == true) {
            FiscalYear::where('status', true)->update(['status' => false]);
        }

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

        $fy = new FiscalYearResource($fiscalYear);
        return $fy;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFiscalYearRequest $request, FiscalYear $fiscalYear)
    {
        //

        $data = $request->Validated();

        if ($data['status'] == true) {
            FiscalYear::where('status', true)->update(['status' => false]);
        }

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

    public function activeFiscalYear()
    {

        $fiscalYear = FiscalYear::where('status', true)->first();
        return response()->json([
            'activeYear' => $fiscalYear
        ]);
    }

    public function orderFiscalYears(Request $request)
    {

        $orderedFiscalYears = $request->fiscalYearOrder;
        foreach ($orderedFiscalYears as $order => $fyId) {
            FiscalYear::where('id', $fyId)->update(['order' => $order]);
        }

        return response()->json(['message' => 'Order of fiscalYears saved successfully']);
    }
}
