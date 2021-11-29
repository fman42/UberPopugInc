<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Services\ProduceEvent\Producer;
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
            $this->throwEventException('TaskCreated');
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
            $this->producer->makeEvent('Task', 'Assigned', $event);
        } else {
            $this->throwEventException('TaskAssigned');
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
            $this->throwEventException('TaskCompleted');
        }

        return redirect()->back();
    }

    public function ReassignTasks()
    {
        $open_tasks = Task::noCompleted()->get();
        foreach ($open_tasks as $task) {
            $this->assignTask($task);
        }

        $this->producer->makeEvent('Task', 'Reassigned', [
            'made_user_id' => Session::get('user_session')['public_user_id']
        ]);

        return redirect()->back();
    }

    public function GetTask()
    {
        $user_id = Session::get('user_session')['public_user_id'] ?? 16;
        return view('dashboard', [
            'tasks' => Task::where('assigned_user_id', $user_id)->noCompleted()->get()
        ]);
    }
}
