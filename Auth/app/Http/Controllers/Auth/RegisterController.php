<?php

namespace App\Http\Controllers\Auth;

use App\Services\SchemaRegistry\ValidatorSchemaRegistry;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Services\ProduceEvent\Producer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => 'employee',
            'password' => Hash::make($data['password']),
        ]);
    
        $event = [
            'public_id' => $user->id,
            'role' => $user->role,
            'name' => $user->name,
            'email' => $user->email
        ];

        if (ValidatorSchemaRegistry::check($event, 'Auth', 'AccountCreated')) {
            $this->producer->makeEvent('AccountsStream', 'Created', $event);    
        } else {
            $this->throwEventException('AccountCreated');
        }

        return $user;
    }
}
