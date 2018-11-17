<?php namespace App\Models;

use App\Observers\InvestorObserver;
use App\Observers\JudgeObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InvestorProfile
 * @package App\Models
 * @property User $user
 * @property string $company_name
 * @property string $title
 * @property string $phone
 * @property string $education
 * @property boolean $approval
 * @property boolean $submit
 * @property Industry[] $interested_industries
 * @property SubIndustry[] $interested_sub_industries
 * @property Competition[] $participating_competitions
 * @property integer $current_step
 */
class InvestorProfile extends Model
{
    protected $fillable = [
        'company_name',
        'company_description',
        'title',
        'phone',
        'education',
        'refer',
        'experience',
        'linkedin',
        'approval',
        'submit',
        'current_step',
    ];

    public $timestamps = false;

    public function participating_competitions()
    {
        return $this->hasMany(InvestorCompetition::class);
    }

    public function sub_competitions()
    {
        return $this->belongsToMany(SubCompetition::class, 'investor_sub_competitions');
    }

    public function interested_industries()
    {
        return $this->belongsToMany(Industry::class, 'investor_industries')->orderBy('industry_id');
    }

    public function interested_sub_industries()
    {
        return $this->belongsToMany(SubIndustry::class, 'investor_sub_industries')->orderBy('sub_industry_id');
    }

    public function resume()
    {
        return $this->belongsTo(File::class, 'resume_file_id');
    }

    public function photo()
    {
        return $this->belongsTo(File::class, 'photo_file_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        parent::observe(InvestorObserver::class);
    }
}
