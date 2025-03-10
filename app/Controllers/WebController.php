<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class WebController extends Controller
{
    

    public function login()
    {
        return view('auth/login'); // Menampilkan halaman login
    }

    public function register()
    {
        return view('auth/register'); // Menampilkan halaman register
    }

    public function management()
    {
        return view('management'); // Menampilkan halaman register
    }
}
