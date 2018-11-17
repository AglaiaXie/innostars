<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Competition
 *
 * @method static Builder isPreliminary()
 * @method static Builder isSemifinal()
 * @method static Builder isFinal()
 * @method static Competition create(array $params)
 * @method static Builder where($column, $operator = '=', $value)
 * @property Carbon $date
 * @property Carbon $deadline
 * @property User $admin
 * @property string $name
 * @property JoinedCompetition[] $companies
 */
class Competition extends Model
{
    const NAME_ONLINE = 'Online Stage';
    const NAME_PRELIMINARY_STAGE = 'Preliminary Stage';
    const NAME_SEMI_FINAL = 'Industry Final';
    const NAME_FINAL = 'Grand Final';

    protected $fillable = [
        'name',
        'description',
        'city',
        'in_session',
        'date',
        'deadline',
    ];

    public $timestamps = false;

    public $dates = [
        'date', 'deadline'
    ];

    protected $appends = [
        'total_companies',
        'total_promoted',
        'total_judges',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    public function judges()
    {
        return $this->hasMany(JudgingCompetition::class)
            ->where('approval', true)
            ->whereHas('judge', function (Builder $builder) {
                $builder->where('approval', true);
            });
    }

    public function getTotalJudgesAttribute()
    {
        return $this->judges()->count();
    }

    public function companies()
    {
        return $this->hasMany(JoinedCompetition::class)
            ->where('approval', true)
            ->whereHas('company', function(Builder $builder) {
                $builder->where('approval', true);
            });
    }

    public function getTotalCompaniesAttribute()
    {
        return $this->companies()->count();
    }

    public function getTotalPromotedAttribute()
    {
        return $this->companies()->where('promoted', '=', 1)->count();
    }

    public function allJudges()
    {
        return $this->hasMany(JudgingCompetition::class)
            ->whereHas('judge', function (Builder $builder) {
                $builder->where('approval', true);
            });
    }

    public function allCompanies()
    {
        return $this->hasMany(JoinedCompetition::class)
            ->whereHas('company', function(Builder $builder) {
                $builder->where('approval', true);
        });
    }

    public function allPartners()
    {
        return $this->hasMany(PartnerCompetition::class)
            ->whereHas('partner', function(Builder $builder) {
                $builder->where('approval', true);
            });
    }

    public function allInvestors()
    {
        return $this->hasMany(InvestorCompetition::class)
            ->whereHas('investor', function(Builder $builder) {
                $builder->where('approval', true);
            });
    }

    public function industries()
    {
        return $this->belongsToMany(Industry::class, 'competition_industries');
    }

    public function sub_competitions()
    {
        return $this->hasMany(SubCompetition::class);
    }

    public function getCityAttribute()
    {
        if ($this->sub_competitions()->exists()) {
            return object_get($this->industries()->first(), 'name');
        }

        return $this->attributes['city'];
    }

    public function scopeIsOnline(Builder $builder) {
        return $builder->where('name', self::NAME_ONLINE);
    }

    public function scopeIsPreliminary(Builder $builder) {
        return $builder->where('name', self::NAME_PRELIMINARY_STAGE);
    }

    public function scopeIsSemifinal(Builder $builder) {
        return $builder->where('name', self::NAME_SEMI_FINAL);
    }

    public function scopeIsFinal(Builder $builder) {
        return $builder->where('name', self::NAME_FINAL);
    }

    public function refreshRank($industryId)
    {
        $this->companies()->where('promoted', '=', 1)
            ->whereHas('company', function (Builder $builder) use ($industryId) {
                $builder->where('industry_id', $industryId);
            })->orderBy('score_average', 'desc')->get()->each(function (JoinedCompetition $company, $index) {
                if ($company->industry_rank !== $index + 1) {
                    $company->update(['industry_rank' => $index + 1]);
                }
            });
    }
}
