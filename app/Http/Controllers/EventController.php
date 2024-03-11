<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
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
        $activeFiscalYearId = request()->input('fiscal_year_id');
        // $events = Event::with('categories', 'company', 'fiscalYear', 'tasks')->where('company_id', Auth::user()->company_id)->latest()->get();

        $query = Event::query()->with('categories', 'company', 'fiscalYear', 'tasks');;


        if ($activeFiscalYearId) {
            $query->where('fiscal_year_id', $activeFiscalYearId);
        }
        if (Auth::check()) {
            $query->where('company_id', Auth::user()->company_id)->orderBy('ad_date');
        }


        $events = $query->orderBy('ad_date')->get();


        return EventResource::collection($events);
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
        return  new EventResource($event);
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


    public function copySelectedEvents(Request $request)
    {

        // Retrieve input parameters
        $destinationCompanyIds = $request->target_companies;
        $targetEventIds = $request->target_events;

        // Retrieve data associated with the source company
        $sourceCompany = Company::find(Auth::user()->company_id);

        // Retrieve events based on the target event IDs
        $events = Event::with('tasks', 'categories')
            ->whereIn('id', $targetEventIds)
            ->where('company_id', $sourceCompany->id)
            ->get();

        // Iterate over each event
        foreach ($events as $event) {
            // Duplicate the event for each destination company
            foreach ($destinationCompanyIds as $destinationCompanyId) {
                $newEvent = $event->replicate(); // Create a replica of the event
                $newEvent->company_id = $destinationCompanyId; // Update company ID
                $newEvent->save(); // Save the replicated event

                // Duplicate associated categories
                $newEvent->categories()->attach($event->categories->pluck('id')->toArray());

                // Optionally, duplicate associated tasks
                if ($event->tasks->isNotEmpty()) {
                    foreach ($event->tasks as $task) {
                        $newTask = $task->replicate(); // Create a replica of the task
                        $newTask->event_id = $newEvent->id; // Update event ID
                        $newTask->documents = null; // Clear documents (if needed)
                        $newTask->save(); // Save the replicated task
                    }
                }
            }
        }


        // Optionally, handle other related data

        return response()->json(['message' => 'Data copied successfully']);
    }
    public function dublicateEvents(Request $request)
    {


        $targetEventIds = $request->target_events;

        // Retrieve data associated with the source company
        $sourceCompany = Company::find(Auth::user()->company_id);

        // Retrieve events based on the target event IDs
        $events = Event::with('tasks', 'categories')
            ->whereIn('id', $targetEventIds)
            ->where('company_id', $sourceCompany->id)
            ->get();

        // Iterate over each event
        foreach ($events as $event) {

            $newEvent = $event->replicate();
            $newEvent->save();

            // Duplicate associated categories
            $newEvent->categories()->attach($event->categories->pluck('id')->toArray());

            // Optionally, duplicate associated tasks
            if ($event->tasks->isNotEmpty()) {
                foreach ($event->tasks as $task) {
                    $newTask = $task->replicate();
                    $newTask->event_id = $newEvent->id; // Update event ID
                    $newTask->documents = null; // Clear documents (if needed)
                    $newTask->save(); // Save the replicated task
                }
            }
        }


        // Optionally, handle other related data

        return response()->json(['message' => 'Data copied successfully']);
    }
}
