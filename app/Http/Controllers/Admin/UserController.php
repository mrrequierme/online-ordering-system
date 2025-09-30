<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        $users = User::orderByRaw("
            case
            when role = 'admin' then 1
            when role = 'staff' then 2
            else 3
            end
        ")
        ->orderBy('name','asc')
        ->get();

        return view('admins.users.index',compact('users'));
    }

    public function create(){
        return view('admins.users.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'gender' => 'required|in:male,female',
        'birthday' => 'required|date|before:today',
        'address' => 'required|string|max:255',
        'contact' => 'required|string|max:20',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:staff,admin',
    ]);

    User::create([
        'name' => $validated['name'],
        'gender' => $validated['gender'],
        'birthday' => $validated['birthday'],
        'address' => $validated['address'],
        'contact' => $validated['contact'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
    ]);

    return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
}

}
