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
        $tasks = Task::latest()->get();

        // TODO::using API resources makes it easy when we need to have conditional data or transform then
        // Check out: https://laravel.com/docs/10.x/eloquent-resources#main-content
        return $tasks;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('documents')) {
            $data['documents'] = Storage::disk('s3')->putFile('task-documents', $request->file('documents'));
        }

        $task = Task::create($data);

        return response()->json([
            'task' => $task,
            'message' => "Successfully created"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return $task;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $data = $request->validated();

        if ($request->file('documents')) {

            if ($task->documents) {
                Storage::disk('s3')->delete($task->documents);
            }
            $data['documents'] = Storage::disk('s3')->putFile('task-documents', $request->file('documents'));
        } else {
            $data['documents'] = $task->documents;
        }
        $task->update($data);

        return response()->json([
            'task' => $task,
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
            'task' => $task,
            'message' => "Successfully Deleted"
        ]);
    }

    public function getTasks(Request $request)
    {
        $tasks = Task::where('event_id', $request->event_id)->latest()->get();

        // TODO::refactor this to API resource
        $tasks->map(function ($task) {
            $task->documents = $task->documentUrl();
            return $task;
        });
        
        return $tasks;
    }
}
