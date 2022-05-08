<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function GetRedirectAuthLink()
    {
        $query = http_build_query([
            'client_id' => config('auth.oauth_key'),
            'redirect_uri' => config('app.url').'/callback',
            'response_type' => 'code',
            'scope' => '*',
        ]);

        return redirect(config('auth.url').'/oauth/authorize?'.$query);
    }

    public function Callback(Request $request)
    {  
        $code = '0af3fad0f619494712cb6f676d2b3eb7eb0670e1d61e7db2af825e33492200ca6716ca34e4cdf933';
        $request = Http::asForm()->get(config('auth.url').'/api/user/'.$code);
        
        $user = User::where('p_id', $request['p_id'])->first() ?? new User();
        $user->name = $request['user']['name'];
        $user->email = $request['user']['email'];
        $user->p_id = $request['p_id'];
        $user->role = $request['user']['role'];
        $user->save();

        Auth::login($user);
        return redirect('/');
    }
}
