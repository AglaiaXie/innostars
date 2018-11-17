<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Industry;
use App\Models\JoinedCompetition;
use App\Models\JudgingCompetition;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PragmaRX\Countries\Facade;

class RegisterController extends Controller
{
    const PARTICIPANT_TYPE = 'participant';
    const JUDGE_TYPE = 'judge';
    const INVESTOR_TYPE = 'investor';
    const PARTNER_TYPE = 'partner';

    protected $commonRule = [
        'first_name' => 'required|string|max:255',
        'last_name'  => 'required|string|max:255',
        'email'      => 'required|string|email|max:255|unique:users',
        'password'   => 'required|string|min:6|confirmed',
    ];

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => bcrypt($data['password']),
        ]);
    }

    /**
     * Show the application registration form.
     *
     * @return Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  Request $request
     * @return Response
     */
    public function register(Request $request)
    {
        $request->validate($this->commonRule + [
                'agree_term' => 'required',
                'user_type' => 'required|in:' . implode(',', [
                        self::JUDGE_TYPE,
                        self::PARTICIPANT_TYPE,
                        self::INVESTOR_TYPE,
                        self::PARTNER_TYPE,
                    ]),
            ], [
                'agree_term.required' => 'You must agree to the terms and conditions',
            ]);

        event(new Registered($user = $this->create($request->all())));

        if ($request->get('sxsw') === true) {
            $user->sxsw = true;
            $user->save();
        }

        switch ($request->get('user_type')) {
            case self::PARTICIPANT_TYPE:
                $user->roles()->save(Role::where('name', 'participant')->first());
                $user->company()->create([]);
                $user->company->joined_competitions()->save(new JoinedCompetition([
                    'competition_id' => Competition::where('name', Competition::NAME_ONLINE)->first()->getKey()
                ]));
                break;
            case self::JUDGE_TYPE:
                $user->roles()->save(Role::where('name', 'judge')->first());
                $user->judge_profile()->create([]);
                $user->judge_profile->judging_competitions()->save(new JudgingCompetition([
                    'competition_id' => Competition::where('name', Competition::NAME_ONLINE)->first()->getKey()
                ]));
                break;
            case self::INVESTOR_TYPE:
                $user->roles()->save(Role::where('name', 'new_investor')->first());
                $user->investor_profile()->create([]);
                break;
            case self::PARTNER_TYPE:
                $user->roles()->save(Role::where('name', 'new_partner')->first());
                $user->partner_profile()->create([]);
                break;
        }

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
}
