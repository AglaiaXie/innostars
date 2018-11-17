<?php namespace App\Models;

use App\Observers\JudgeObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class JudgeProfile
 * @package App\Models
 * @property User $user
 * @property string $company_name
 * @property string $title
 * @property string $phone
 * @property string $education
 * @property string $refer
 * @property boolean $approval
 * @property boolean $submit
 * @property Industry[] $judging_industries
 * @property Industry[] $interested_industries
 * @property Competition[] $judging_competitions
 * @property SubIndustry[] $judging_sub_industries
 * @property SubIndustry[] $interested_sub_industries
 * @property integer $current_step
 */
class JudgeProfile extends Model
{
    protected $fillable = [
        'company_name',
        'company_description',
        'position',
        'title',
        'phone',
        'education',
        'refer',
        'experience',
        'approval',
        'submit',
        'current_step',
    ];

    protected $appends = [
        'preliminary_competitions',
        'semifinal_competitions',
        'final_competitions',
    ];

    public $timestamps = false;

    public function judging_industries()
    {
        return $this->belongsToMany(Industry::class, 'judging_industries')->orderBy('industry_id');
    }

    public function judging_sub_industries()
    {
        return $this->belongsToMany(SubIndustry::class, 'judging_sub_industries')->orderBy('sub_industry_id');
    }

    public function judging_competitions()
    {
        return $this->hasMany(JudgingCompetition::class);
    }

    public function judging_sub_competitions()
    {
        return $this->belongsToMany(SubCompetition::class, 'judging_sub_competitions');
    }

    public function interested_industries()
    {
        return $this->belongsToMany(Industry::class, 'interested_industries')->orderBy('industry_id');
    }

    public function interested_sub_industries()
    {
        return $this->belongsToMany(SubIndustry::class, 'judging_sub_industries')->orderBy('sub_industry_id');
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

    public function getPreliminaryCompetitionsAttribute()
    {
        return $this->judging_competitions()->whereHas('competition', function (Builder $builder) {
            $builder->where('name', Competition::NAME_PRELIMINARY_STAGE);
        })->whereHas('scores', function (Builder $builder) {
            $builder->where('submit', false);
        })->get();
    }

    public function getSemifinalCompetitionsAttribute()
    {
        return $this->judging_competitions()->whereHas('competition', function (Builder $builder) {
            $builder->where('name', Competition::NAME_SEMI_FINAL);
        })->whereHas('scores', function (Builder $builder) {
            $builder->where('submit', false);
        })->get();
    }

    public function getFinalCompetitionsAttribute()
    {
        return $this->judging_competitions()->whereHas('competition', function (Builder $builder) {
            $builder->where('name', Competition::NAME_FINAL);
        })->whereHas('scores', function (Builder $builder) {
            $builder->where('submit', false);
        })->get();
    }

    public static function boot()
    {
        parent::boot();

        parent::observe(JudgeObserver::class);
    }
}
