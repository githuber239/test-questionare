<?php
namespace App\Http\Controllers;

use Auth, Request, Cookie;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest', ['except' => ['getLogout']]);
    }

    public function before()
    {
        parent::before();
        $this->title = 'Авторизация';
    }

    public function getIndex()
    {
        return redirect('auth/login');
    }

    public function getLogin()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        session()->regenerate();
    }

    public function postLogin()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        $remember = Request::input('remember', 0);
        $remember = boolval($remember);

        $check = User::where('name', '=', (string)Request::input('username'))->first();
        if (!$check) {
            return redirect('auth/login')->withInput(Request::except('password'))
                ->with('error', 'Неверный логин или пароль');
        }

        if (Auth::attempt(['name' => Request::input('username'), 'password' => Request::input('password')], $remember)) {
            return redirect()->intended('/');
        }
        return redirect('auth/login')->withInput(Request::except('password'))
            ->with('error', 'Неверный логин или пароль');
    }



    public function getLogout() {
        if(Auth::user()) {
            Auth::logout();
        }
        return back();
    }
}
