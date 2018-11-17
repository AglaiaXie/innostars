<?php namespace App\Models;

use App\Observers\JoinedCompetitionObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class JoinedCompetition
 * @package App\Models
 * @property Competition          $competition
 * @property Score[]              $scores
 * @property Company              $company
 * @property JudgingCompetition[] $judges
 * @property boolean $approval
 * @property boolean $promoted
 * @property integer $scored
 * @property integer $total_score
 * @property float   $score_average
 * @property integer $industry_rank
 * @method static Builder competitionType(string $type)
 */
class JoinedCompetition extends Model
{
    CONST MAX_JUDGE = 3;

    protected $fillable = [
        'company_id',
        'competition_id',
        'approval',
        'promoted',
        'score_average',
        'industry_rank',
        'final_sum_1',
        'final_sum_2',
        'final_sum_3',
    ];

    protected $appends = [
        'judges'
    ];

    protected $casts = ['approval' => 'boolean', 'promoted' => 'boolean'];

    public $timestamps = false;

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function getJudgesAttribute()
    {
        return $this->scores()->count();
    }

    public function scopeIsPreliminary(Builder $builder) {
        return $builder->whereHas('competition', function (Builder $builder) {
            $builder->isPreliminary();
        });
    }

    public function getTotalScoreAttribute()
    {
        return $this->scores()->count();
    }

    public function getScoredAttribute()
    {
        return $this->scores()->where('submit', true)->count();
    }

    public function scopeCompetitionType(Builder $builder, string $type)
    {
        return $builder->whereHas('competition', function (Builder $builder) use ($type) {
            $builder->where('name', $type);
        });
    }

    public function refreshScore()
    {
        $scores = $this->scores()->where('submit', true)->get();
        $this->update([
            'score_average' => number_format($scores->avg('total_score'), 1) + 0,
            'final_sum_1' => number_format($scores->avg('final_sum_1'), 1) + 0,
            'final_sum_2' => number_format($scores->avg('final_sum_2'), 1) + 0,
            'final_sum_3' => number_format($scores->avg('final_sum_3'), 1) + 0,
        ]);
    }
}
