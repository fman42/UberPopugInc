<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use Root\SchemaRegistry\SchemaValidator;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Services\ProduceEvent\Producer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $producer;

    public function __construct(Producer $producer)
    {
        $this->producer = $producer;
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        DB::beginTransaction();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role_id' => Role::defaultRole()->id,
            'public_id' => (string) Str::uuid(),
            'password' => Hash::make($data['password']),
        ]);
    
        $event = [
            'data' => (object) [
                'public_id' => $user->public_id,
                'role' => 'admin', 
                'name' => $user->name,
                'email' => $user->email
            ]
        ];

        if (SchemaValidator::check($event, 'Auth', 'AccountCreated')) {
            $this->producer->makeEvent('AccountsStream', 'Created', $event);    
            DB::commit();
        } else {
            $this->throwEventException('AccountCreated');
        }

        return $user;
    }
}
