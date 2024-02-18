<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\FiscalYear;
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
}
