<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Repositories\TaskRepository;

class TaskController extends Controller
{
    protected $tasks;

    public function __construct(TaskRepository $tasks)
    {
        $this->middleware('auth');
        $this->tasks = $tasks;
    }

    public function index(Request $request)
    {
        return view('tasks.index', [
            'tasks' => $this->tasks->forUser($request->user()),
        ]);
    }

    public function edit($id)
    {
        $task = Task::whereId($id)->first();
        return view('tasks.edit')->with('task', $task);
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id)->update($request->all());
        return redirect('/tasks')->with('success', 'Berhasil');
    }

    public function destroy(Request $request, Task $task)
    {
        $task->delete('destroy', $task);
        return redirect()->back()->with('success', 'Task Deleted');
    }

    public function store (Request $request)
    {
        $this->validate ($request, [
            'name' => 'required | max : 255',
            ]);
            $request->user()->tasks()->create([
            'name'=>$request->name
            ]);
            return redirect ('/tasks');
    }
}
