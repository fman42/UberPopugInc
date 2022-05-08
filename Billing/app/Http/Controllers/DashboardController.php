<?php

namespace App\Http\Controllers;

use App\Models\{Audit, Task, User};

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::find(80);

        $topManagmentFee = $user->role == 'admin' || $user->role == 'fin' ? (Task::toDay()->completed()->sum('ammount') + Task::toDay()->active()->sum('ammount')) * -1 : 0;
        $audits = Audit::toDay()->where('user_id', $user->id)->get();

        return view('main', compact('topManagmentFee', 'audits', 'user'));
    }
}
