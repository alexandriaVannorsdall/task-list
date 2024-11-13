<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Resources\TaskListResource;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class TaskListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'sort_by' => ['nullable', 'in:created_at,updated_at,title'],
            'sort_dir' => ['nullable', 'in:asc,desc'],
            'search' => ['nullable', 'string'],
        ]);

        $query = TaskList::where('user_id', Auth::id());

        if ($request->search ?? false) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        $paginated = $query
            ->orderBy($request->sort_by ?? 'created_at', $request->sort_dir ?? 'asc')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'task_lists' => TaskListResource::collection($paginated),
            'total' => $paginated->total(),
            'page' => $paginated->currentPage(),
            'lastPage' => $paginated->lastPage(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => ['required', 'string']
        ]);

        $taskList = new TaskList();
        $taskList->user_id = Auth::id();
        $taskList->title = $request->title;
        $taskList->save();

        return response()->json([
            'status' => 'success',
            'task_list' => new TaskListResource($taskList),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskList $taskList): JsonResponse
    {
        abort_unless($taskList->user_id === Auth::id(), 403);

        return response()->json([
            'status' => 'success',
            'task_list' => new TaskListResource($taskList),
        ]);
    }

    public function tasks(TaskList $taskList): JsonResponse
    {
        abort_unless($taskList->user_id === Auth::id(), 403);

        return response()->json([
            'status' => 'success',
            'tasks' => TaskResource::collection($taskList->tasks),
        ]);
    }

    public function addTask(Request $request, TaskList $taskList): JsonResponse
    {
        abort_unless($taskList->user_id === Auth::id(), 403);

        $request->validate([
            'title' => ['required', 'string'],
            'details' => ['nullable', 'string'],
        ]);

        $task = new Task();
        $task->user_id = Auth::id();
        $task->task_list_id = $taskList->id;
        $task->status = TaskStatus::ToDo->value;
        $task->title = $request->title;
        $task->details = $request->details;
        $task->save();

        return response()->json([
            'status' => 'success',
            'task' => new TaskResource($task),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskList $taskList): JsonResponse
    {
        abort_unless($taskList->user_id === Auth::id(), 403);

        $request->validate([
            'title' => ['required', 'string']
        ]);

        $taskList->title = $request->title;
        $taskList->save();

        return response()->json([
            'status' => 'success',
            'task_list' => new TaskListResource($taskList),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskList $taskList): JsonResponse
    {
        abort_unless($taskList->user_id === Auth::id(), 403);

        $taskList->delete();

        return response()->json(['status' => 'success']);
    }
}