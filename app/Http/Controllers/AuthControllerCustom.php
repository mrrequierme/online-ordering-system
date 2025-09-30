<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function index(){
        return view('auth.register');
    }

    public function store(Request $request){
         $data = $request->validate([
            'name' => 'required|string',
            'gender' => 'required|string',
            'birthday' => 'required|date|before_or_equal:today',
            'address' => 'required|string',
            'contact' => 'required|string|min:11|max:13',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);
        $user = User::create($data);
        Auth::login($user);

       return redirect()->route('user.orders.index');
        
    }

      public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the CSRF token to prevent fixation
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

   public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
            case 'staff':
                return redirect()->route('admin.orders.index');

            case 'user':
                return redirect()->route('user.orders.index');

            default:
                // fallback if role is missing or unknown
                return redirect()->intended('/'); // 👈 will go to '/' (home) if no intended URL exists
        }
    }

    throw ValidationException::withMessages([
        'error' => 'Invalid credentials!',
    ]);
}

}
