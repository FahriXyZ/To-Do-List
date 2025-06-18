<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class TaskController extends Controller
{
    public function index()
    {
        return Task::where('user_id', Auth::id())->get();
    }

    public function store(Request $request)
    {
        return Task::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'completed' => false
        ]);
    }

    public function update($id)
    {
        $task = Task::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $task->completed = !$task->completed;
        $task->save();
        return $task;
    }

    public function destroy($id)
    {
        Task::where('id', $id)->where('user_id', Auth::id())->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
