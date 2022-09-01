<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Diagnos;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    # Панель администратора
    public function panel() {
        $diagnoses = Diagnos::all();
        $questions = Question::all();
        $answers = Answer::all();
        return view('panel', compact('diagnoses', 'questions', 'answers'));
    }

    # Страница авторизации
    public function auth() {
        return view('auth');
    }

    # Аутентификация в админку
    public function login(Request $request) {
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('panel');
        }

        return back()->withErrors([
            'name' => 'Неверное логин или пароль',
            'password' => 'Неверное логин или пароль',
        ]);
    }

    # Выход из админ панели
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
