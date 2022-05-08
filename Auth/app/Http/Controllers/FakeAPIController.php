<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FakeAPIController extends Controller
{
    public function getUser($code)
    {
        $user = \DB::table('oauth_auth_codes')->where('id', $code)->first();
        if ($user === null) {
            abort(404);
        }

        return response()->json(['user' => User::find($user->user_id), 'p_id' => $user->id]);
    }
}
