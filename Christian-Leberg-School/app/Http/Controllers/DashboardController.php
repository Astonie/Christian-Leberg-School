<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect()->route('dashboard.admin');
        } elseif ($user->hasRole('teacher')) {
            return redirect()->route('dashboard.teacher');
        } elseif ($user->hasRole('student')) {
            return redirect()->route('dashboard.student');
        } elseif ($user->hasRole('guardian')) {
            return redirect()->route('dashboard.guardian');
        }

        return view('dashboard', ['user' => $user]);
    }

    public function admin()
    {
        return view('dashboards.admin');
    }

    public function teacher()
    {
        return view('dashboards.teacher');
    }

    public function student()
    {
        return view('dashboards.student');
    }

    public function guardian()
    {
        return view('dashboards.guardian');
    }
}
