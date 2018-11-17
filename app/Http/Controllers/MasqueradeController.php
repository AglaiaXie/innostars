<?php namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MasqueradeController extends Controller
{
    public function go(User $user)
    {
        Auth::login($user);

        return redirect('/');
    }
}
