<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve tasks only for the authenticated user
        $tasks = Auth::user()->tasks;
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);
        
        // Create a task associated with the authenticated user
        Auth::user()->tasks()->create($request->only(['title', 'description']));
        return redirect()->route('tasks.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Task $task, Request $request)
    {
        // Ensure the task belongs to the authenticated user
        if (Auth::user()->id !== $task->user_id) {
            abort(403, 'Unauthorized action.');
        }
    
        // Validate request data
        $validatedData = $request->validate([
            'completed' => 'boolean',
        ]);

        // Update task attributes
        $task->update([
            'completed' => $request->has('completed') ? (bool)$request->input('completed') : false,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        // Ensure the task belongs to the authenticated user
        if (Auth::user()->id !== $task->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $task->delete();
        return redirect()->route('tasks.index');
    }
}