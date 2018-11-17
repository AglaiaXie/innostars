<?php namespace App\Models;

use App\Observers\JudgeObserver;
use App\Observers\PartnerObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InvestorProfile
 * @package App\Models
 * @property User $user
 * @property string $company_name
 * @property string $title
 * @property string $phone
 * @property boolean $approval
 * @property boolean $submit
 * @property Competition[] $participating_competitions
 * @property integer $current_step
 */
class PartnerProfile extends Model
{
    protected $fillable = [
        'company_name',
        'company_description',
        'contact_person',
        'title',
        'phone',
        'email',
        'refer',
        'reason',
        'approval',
        'submit',
        'current_step',
    ];

    public $timestamps = false;

    public function participating_competitions()
    {
        return $this->hasMany(PartnerCompetition::class);
    }

    public function document()
    {
        return $this->belongsTo(File::class, 'document_file_id');
    }

    public function real_logo() {
        return $this->belongsTo(File::class, 'real_logo_file_id');
    }

    public function logo()
    {
        return $this->belongsTo(File::class, 'logo_file_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        parent::observe(PartnerObserver::class);
    }
}
