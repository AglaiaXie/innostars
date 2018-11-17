<?php namespace App\Models;

use Cmgmyr\Messenger\Traits\Messagable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

/**
 * Class User
 *
 * @property Company         $company
 * @property JudgeProfile    $judge_profile
 * @property InvestorProfile $investor_profile
 * @property PartnerProfile  $partner_profile
 * @property string          $email
 * @property string          $first_name
 * @property string          $last_name
 * @property boolean         $sxsw
 * @property string          $type
 */
class User extends Authenticatable
{
    use Notifiable, EntrustUserTrait, Messagable;

    const PARTICIPANT_HOME_URL = '/participant/profile/company';
    const PARTICIPANT_STATUS_URL = '/participant';
    const JUDGE_HOME_URL = '/judge/profile/information';
    const JUDGE_STATUS_URL = '/judge';
    const INVESTOR_HOME_URL = '/investor/profile/information';
    const PARTNER_HOME_URL = '/partner/profile/information';
    const ADMIN_HOME_URL = '/admin/participants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'sxsw',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'sxsw' => 'boolean',
    ];

    protected $appends = ['type'];

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function judge_profile()
    {
        return $this->hasOne(JudgeProfile::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function investor_profile()
    {
        return $this->hasOne(InvestorProfile::class);
    }

    public function partner_profile()
    {
        return $this->hasOne(PartnerProfile::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function semifinal_form()
    {
        return $this->hasOne(SemifinalForm::class);
    }

    public function final_form()
    {
        return $this->hasOne(FinalForm::class);
    }

    public function getTypeAttribute()
    {
        return array_last(explode('_', object_get($this->roles()->first(), 'name')));
    }

    public function competitions()
    {
        switch ($this->type) {
            case 'participant':
                return $this->company->joined_competitions->pluck('competition_id')->toArray();
            case 'judge':
                return $this->judge_profile->judging_competitions->pluck('competition_id')->toArray();
            case 'partner':
                return $this->partner_profile->participating_competitions->pluck('competition_id')->toArray();
            case 'investor':
                return $this->investor_profile->participating_competitions->pluck('competition_id')->toArray();
            default:
                return [];
        }
    }
}
