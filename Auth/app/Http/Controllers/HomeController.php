<?php

namespace App\Http\Controllers;

use Root\SchemaRegistry\SchemaValidator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ProduceEvent\Producer;
use Auth;

class HomeController extends Controller
{
    private $producer;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Producer $producer)
    {
        $this->producer = $producer;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $usersCollection = Auth::user()->role === 'admin' ? User::get()->toArray() : [];
        return view('home', compact("usersCollection"));
    }

    public function getUser($id)
    {
        $user = User::find($id);
        return $user === null ? abort(404) : view('users.edit', compact("user"));
    }

    public function deleteUser(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $event = [
            'data' => (object) [
                'public_id' => $user->public_id
            ]
        ];

        if (SchemaValidator::check($event, 'Auth', 'AccountDeleted')) {
            $this->producer->makeEvent('AccountsStream', 'Deleted', $event);
            $user->delete();
        } else {
            $this->throwEventException('AccountDeleted');
        }

        return redirect()->back();
    }

    public function updateRoleUser($id, Request $request)
    {
        $user = User::find($id);
        $user->role = $request->role;

        $event = [
            'public_user_id' => $user->id,
            'role' => $user->role,
            'name' => $user->name,
            'email' => $user->email
        ];

        if (SchemaValidator::check($event, 'Auth', 'AccountUpdated')) {
            $this->producer->makeEvent('AccountsStream', 'Updated', $event);
            $user->save();
        } else {
            $this->throwEventException('AccountUpdated');
        }

        return redirect()->back();
    }
}
