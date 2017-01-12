<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register()
    {
        return view('layouts.secondary', [
            'page' => 'pages.register',
            'title' => 'Регистрация в блоге',
            'content' => '',
            'activeMenu' => 'register',
        ]);
    }

    public function registerPost()
    {
        $this->validate($this->request, [
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|max:255|min:6',
            'password2' => 'required|same:password',
            'phone' => 'required|regex:/\+\d{1}\s{1}\(\d{3}\)\s{1}\d{3}\-\d{2}\-\d{2}/',
            'is_confirmed' => 'required'
        ]);

        DB::table('users')->insert([
            'email' => $this->request->input('email'),
            'password' => bcrypt($this->request->input('password')),
            'phone' => $this->request->input('phone'),
            'name' => $this->request->input('name'),
            'created_at' => \Carbon\Carbon::createFromTimestamp(time())->format('Y-m-d H:i:s'),
            'updated_at' => \Carbon\Carbon::createFromTimestamp(time())->format('Y-m-d H:i:s'),
        ]);

        return redirect()->route('site.auth.login');
    }

    public function login()
    {
        return view('layouts.secondary', [
            'page' => 'pages.login',
            'title' => 'Вход в систему',
            'content' => '<p>Привет, меня зовут Дмитрий Юрьев и я веб разработчик!</p>',
            'activeMenu' => 'feedback',
        ]);
    }

    public function loginPost()
    {
        $authResult = Auth::attempt([
            'email' => $this->request->input('email'),
            'password' => $this->request->input('password')
        ]);

        if ($authResult) {
            return redirect()->route('site.main.index');
        } else {
            return redirect()->route('site.auth.login')->with('authError', 'Неправильный логин или пароль');
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->back();
    }
}
