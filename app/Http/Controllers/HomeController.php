<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect(url(User::ADMIN_HOME_URL));
        }

        if ($user->hasRole('read_admin')) {
            return redirect(url(User::ADMIN_HOME_URL));
        }

        if ($user->hasRole('competition_admin')) {
            return redirect(url(User::ADMIN_HOME_URL));
        }

        if ($user->hasRole('sxsw_admin')) {
            return redirect(url(User::ADMIN_HOME_URL));
        }

        if ($user->hasRole('industry_admin')) {
            return redirect(url(User::ADMIN_HOME_URL));
        }

        if ($user->hasRole('participant')) {
            if (object_get($user, 'company.approval')) {
                return redirect(url(User::PARTICIPANT_STATUS_URL));
            }

            return redirect(url(User::PARTICIPANT_HOME_URL));
        }

        if ($user->hasRole('judge')) {
            if (object_get($user, 'judge_profile.approval')) {
                return redirect(url(User::JUDGE_STATUS_URL));
            }

            return redirect(url(User::JUDGE_HOME_URL));
        }

        if ($user->hasRole('new_investor')) {
            return redirect(url(User::INVESTOR_HOME_URL));
        }

        if ($user->hasRole('investor')) {
            return redirect(url(User::ADMIN_HOME_URL));
        }

        if ($user->hasRole('new_partner')) {
            return redirect(url(User::PARTNER_HOME_URL));
        }

        if ($user->hasRole('partner')) {
            return redirect(url(User::ADMIN_HOME_URL));
        }

        return redirect(url('/login'));
    }
}
