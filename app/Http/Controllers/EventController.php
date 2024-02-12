<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $events = Event::latest()->get();
        return $events;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        //

        $data = $request->validated();
        $event = Event::create($data);

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

        return response()->json([
            'message' => "Deleted Successfully"
        ]);
    }
}
