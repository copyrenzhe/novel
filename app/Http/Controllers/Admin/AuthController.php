<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectTo = '/admin';
    protected $redirectAfterLogout = '/admin/login';
    protected $guard = 'admin';
    protected $loginView = 'admin.login';
    protected $registerView = 'admin.register';
    protected $username = 'username';

    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => 'getLogout']);
    }

    protected function validator(array $data)
    {

        return Validator::make($data, [
            'username' => 'required|max:255|unique:admin',
            'email' => 'required|email|max:255|unique:admin',
            'password' => 'required|confirmed|min:6',
        ]);

    }

    protected function create(array $data)
    {
        return Admin::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

    }

    public function getLogin()
    {
        dd('test');
        return view($this->loginView);
    }

}