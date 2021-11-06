<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Session;

class MainController extends Controller
{
    public function GetRedirectAuthLink(Request $request)
    {
        $query = http_build_query([
            'client_id' => '94cd661d-b76e-4f42-b0f6-1da5288baf15',
            'redirect_uri' => 'http://127.0.0.1:8000/callback',
            'response_type' => 'code',
            'scope' => '',
        ]);

        return redirect('http://127.0.0.1:8001/oauth/authorize?'.$query);
    }

    public function Callback(Request $request)
    {  
        $request = Http::asForm()->post('http://127.0.0.1:8001/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => '94cd661d-b76e-4f42-b0f6-1da5288baf15',
            'client_secret' => 'wtmvVRqXKgl5BOZh9TlkWxt0FlL00m20EBiXeNCQ',
            'redirect_uri' => 'http://127.0.0.1:8000/callback',
            'code' => $request->code,
        ]);

        $response = $request->json();
        Session::put('user_session', $response);

        return redirect('/');
    }
}
