<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Company;
use App\Models\FiscalYear;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $events = Event::with('categories', 'company', 'fiscalYear', 'tasks')->where('company_id', Auth::user()->company_id)->latest()->get();
        return $events;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        //


        $data = $request->validated();

        $user = Auth::user();
        // return $user->company_id;

        $event = Event::create(array_merge($data, [
            'company_id' => $user->company_id,
        ]));;


        if ($request->has('categories')) {
            $event->categories()->attach($request->categories);
        }

        return response()->json([
            'event' => $event,
            'message' => "Created Successfully"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
        $event->load('categories', 'tasks');
        return  $event;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        //


        $data = $request->validated();

        $event->update($data);
        if ($request->has('categories')) {
            $event->categories()->sync($request->categories);
        }

        return response()->json([
            'message' => "Updated Successfully"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {

        //

        $event->delete();
        $event->categories()->detach();

        return response()->json([
            'message' => "Deleted Successfully"
        ]);
    }

    public function getPaginateEvent(Request $request)
    {
        //
        $perPage = $request->input('per_page', 10);
        $events = Event::with('categories', 'company', 'fiscalYear', 'tasks')->where('company_id', Auth::user()->company_id)->paginate($perPage);
        return $events;
    }

    public function copyEvents(Request $request)
    {


        $destinationCompanyIds = $request->target_companies;
        $sourceFiscalYearId = $request->from_fiscal_year;
        $targetFiscalYearId = $request->to_fiscal_year;
        // Retrieve data associated with the source fiscal year
        $sourceCompany = Company::find(Auth::user()->company_id);
        $events = Event::with('tasks', 'categories')->where('company_id', $sourceCompany->id)->where('fiscal_year_id', $sourceFiscalYearId)->get();

        foreach ($events as $event) {
            // Create a new event associated with the target fiscal year
            foreach ($destinationCompanyIds as $destinationCompanyId) {
                $newEvent = $event->replicate();
                $newEvent->fiscal_year_id = $targetFiscalYearId;
                // $newEvent->date = Carbon::parse($event->date)->addYear();
                $newEvent->company_id = $destinationCompanyId;
                $newEvent->save();

                // categories
                $newEvent->categories()->attach($event->categories->pluck('id')->toArray());


                // Optionally, duplicate associated tasks
                $tasks = $event->tasks;
                foreach ($tasks as $task) {
                    // Create a new task associated with the new event
                    $newTask = $task->replicate();
                    $newTask->event_id = $newEvent->id;
                    $newTask->documents = null;
                    $newTask->save();
                }
            }
        }

        // Optionally, handle other related data

        return response()->json(['message' => 'Data copied successfully']);
    }

    public function copyMyEvents(Request $request)
    {


        $sourceFiscalYearId = $request->from_fiscal_year;
        $targetFiscalYearId = $request->to_fiscal_year;
        // Retrieve data associated with the source fiscal year
        $sourceCompany = Company::find(Auth::user()->company_id);
        $events = Event::with('tasks', 'categories')->where('company_id', $sourceCompany->id)->where('fiscal_year_id', $sourceFiscalYearId)->get();

        foreach ($events as $event) {
            // Create a new event associated with the target fiscal year
            $newEvent = $event->replicate();
            $newEvent->fiscal_year_id = $targetFiscalYearId;
            $newEvent->date = Carbon::parse($event->date)->addYear();
            // $newEvent->company_id = $sourceCompany->id;
            $newEvent->save();

            // categories
            $newEvent->categories()->attach($event->categories->pluck('id')->toArray());


            // Optionally, duplicate associated tasks
            $tasks = $event->tasks;
            foreach ($tasks as $task) {
                // Create a new task associated with the new event
                $newTask = $task->replicate();
                $newTask->event_id = $newEvent->id;
                $newTask->documents = null;
                $newTask->save();
            }
        }
    }
}
