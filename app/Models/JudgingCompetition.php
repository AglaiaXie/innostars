<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class JudgingCompetition
 * @package App\Models
 * @property JudgeProfile $judge
 * @property Competition  $competition
 * @property integer $scoring
 * @property integer $scored
 */
class JudgingCompetition extends Model
{
    protected $fillable = [
        'judge_profile_id',
        'competition_id',
        'approval',
    ];

    public $timestamps = false;

    protected $appends = ['scoring', 'scored'];

    public function judge()
    {
        return $this->belongsTo(JudgeProfile::class, 'judge_profile_id');
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function getScoringAttribute()
    {
        return $this->scores()->where('submit', false)->get()->filter(function($score) {
            return !$score->is_due;
        })->count();
    }

    public function getScoredAttribute()
    {
        return $this->scores()->where('submit', true)->count();
    }
}
