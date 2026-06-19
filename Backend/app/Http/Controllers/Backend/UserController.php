<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('is_active', 1)
                     ->orderBy('created_at', 'desc')
                     ->paginate(15);

        return view('backend.user.index', compact('users'));
    }
}