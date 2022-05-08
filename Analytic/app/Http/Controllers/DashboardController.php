<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Task, User};

class DashboardController extends Controller
{
    public function index()
    {
        $topManagmentFee = (Task::toDay()->completed()->sum('ammount') + Task::toDay()->active()->sum('ammount')) * -1;
        $negativeUserBalanceCount = User::where('balance', '<', 0)->count();
        $expensiveTask = Task::toDay()->orderBy('fee', 'DESC')->first();

        return view('index', compact('topManagmentFee', 'negativeUserBalanceCount', 'expensiveTask'));
    }
}
