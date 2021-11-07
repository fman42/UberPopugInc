<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Services\ProduceEvent\Producer;
use Session;

class TaskController extends Controller
{
    private $producer;

    public function __construct(Producer $producer)
    {
        $this->producer = $producer;
    }

    public function CreateTaskView()
    {
        return view('create_task');
    }

    public function CreateTask(Request $request)
    {
        $data = $request->all();
        $data['assigned_user_id'] = User::inRandomOrder()->first()->id;
        $task = Task::create($data);
        $this->producer->makeEvent('TaskStream', 'Created', $task);
        
        return redirect()->back();
    }
    
    public function CompleteTask($task_id)
    {
        $task = Task::find($task_id);
        $task->completed = 1;
        $task->save();

        $this->producer->makeEvent('Task', 'Completed', $task);
        return redirect()->back();
    }

    public function ReassignTasks()
    {
        $open_tasks = Task::where('completed', 0)->get();
        foreach ($open_tasks as $task) {
            $task->assigned_user_id = User::inRandomOrder()->first()->id;
            $task->save();

            $this->producer->makeEvent('Task', 'Assigned', $task);
        }

        return redirect()->back();
    }

    public function GetTask()
    {
        $user_id = Session::get('user_session')['public_user_id'];
        return view('dashboard', [
            'tasks' => Task::where('completed', 0)->where('assigned_user_id', $user_id)->get()
        ]);
    }
}
