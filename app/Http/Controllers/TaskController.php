<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tasks = Task::latest()->get();
        return $tasks;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        //

        $data = $request->validated();

        if ($request->file('documents')) {
            $data['documents'] = Storage::putFile('task-documents', $request->file('documents'));
        }
        $task = Task::create($data);
        return response()->json([
            'message' => "Successfully created"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
        return $task;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        //


        $data = $request->validated();

        if ($request->file('documents')) {

            if ($task->documents) {
                Storage::delete($task->documents);
            }
            $data['documents'] = Storage::putFile('task-documents', $request->file('documents'));
        } else {
            $data['documents'] = $task->documents;
        }
        $task->update($data);

        return response()->json([
            'message' => "Successfully updated task"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //

        $task->delete();
        return response()->json([
            'message' => "Successfully Deleted"
        ]);
    }

    public function getTasks(Request $request)
    {
        $tasks = Task::where('event_id', $request->event_id)->latest()->get();
        return $tasks;
    }
}
