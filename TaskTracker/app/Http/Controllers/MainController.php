<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Session;

class MainController extends Controller
{
    public function GetRedirectAuthLink()
    {
        $query = http_build_query([
            'client_id' => config('auth.oauth_key'),
            'redirect_uri' => config('app.url').'/callback',
            'response_type' => 'code',
            'scope' => '',
        ]);

        return redirect(config('auth.url').'/oauth/authorize?'.$query);
    }

    public function Callback(Request $request)
    {  
        $request = Http::asForm()->post(config('auth.url').'/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('auth.oauth_key'),
            'client_secret' => config('auth.oauth_secret'),
            'redirect_uri' => config('auth.url').'/callback',
            'code' => $request->code,
        ]);

        $response = $request->json();
        Session::put('user_session', $response);

        return redirect('/');
    }
}
