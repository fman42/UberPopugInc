<?php

namespace App\Http\Controllers;

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
        $usersCollection = [];
        if (Auth::user()->role_id == 'admin') {
            $usersCollection = User::get();
        }

        return view('home', [
            'usersCollection' => $usersCollection
        ]);
    }

    public function getUser($id)
    {
        $user = User::find($id);
        return $user === null ? abort(404) : view('users.edit', ['user' => $user]);
    }

    public function deleteUser(Request $request)
    {
        $user = User::find($request->user_id);
        $this->producer->makeEvent('Deleted', [
            'public_id' => $user->id
        ]);

        $user->delete();
        return redirect()->back();
    }

    public function updateRoleUser($id, Request $request)
    {
        $user = User::find($id);
        $user->role_id = $request->role_id;
        $user->save();

        $this->producer->makeEvent('Updated', [
            'user' => $user
        ]);

        return redirect()->back();
    }
}
