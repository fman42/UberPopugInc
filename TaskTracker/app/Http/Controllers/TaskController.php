<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Services\ProduceEvent\Producer;
use Session;
use App\Services\SchemaRegistry\ValidatorSchemaRegistry;

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
        $task = Task::create($data);
        $event = [
            'id' => $task->id,
            'description' => $task->description,
            'completed' => false
        ];

        if (ValidatorSchemaRegistry::check($event, 'TaskTracker', 'TaskCreated')) {
            $this->producer->makeEvent('TaskStream', 'Created', $event);
            $this->assignTask($task); 
        } else {
            \Log::error('Произошла ошибка при создании новой задачи');
        }
    
        return redirect()->back();
    }
    
    public function assignTask(Task $task)
    {
        $task->assigned_user_id = User::inRandomOrder()->first()->id;
        $task->save();

        $event = [
            'id' => $task->id,
            'assigned_user_id' => $task->assigned_user_id
        ];

        if (ValidatorSchemaRegistry::check($event, 'TaskTracker', 'TaskAssigned')) {
            $this->producer->makeEvent('TaskStream', 'Assigned', $event);
        } else {
            \Log::error('Произошла ошибка при ассайни задачи');
        }
    }

    public function CompleteTask($task_id)
    {
        $task = Task::find($task_id);
        $task->completed = 1;
        $task->save();

        $event = ['id' => $task->id];
        if (ValidatorSchemaRegistry::check($event, 'TaskTracker', 'TaskCompleted')) {
            $this->producer->makeEvent('Task', 'Completed', $event);
        } else {
            \Log::error('Произошла ошибка при завершении задачи');
        }

        return redirect()->back();
    }

    public function ReassignTasks()
    {
        $open_tasks = Task::where('completed', 0)->get();
        foreach ($open_tasks as $task) {
            $task->assigned_user_id = User::inRandomOrder()->first()->id;
            $task->save();

            $this->assignTask($task);
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
