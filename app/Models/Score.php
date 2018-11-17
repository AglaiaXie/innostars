<?php namespace App\Models;

use App\Observers\ScoreObserver;
use Carbon\Carbon;
use function GuzzleHttp\default_ca_bundle;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Score
 * @package App\Models
 *
 * @property JudgingCompetition $judge
 * @property JoinedCompetition $company
 * @property boolean $submit
 * @property Carbon $created_at
 * @property Carbon $due_at
 * @property boolean $is_due
 */
class Score extends Model
{
    protected $guarded =['id'];

    protected $appends = ['total_score', 'due', 'due_at', 'is_due', 'final_sum_1', 'final_sum_2', 'final_sum_3', 'judge_order'];

    public function judge()
    {
        return $this->belongsTo(JudgingCompetition::class, 'judging_competition_id');
    }

    public function company()
    {
        return $this->belongsTo(JoinedCompetition::class, 'joined_competition_id');
    }

    public function getTotalScoreAttribute()
    {
        switch ($this->judge->competition->name) {
            case Competition::NAME_ONLINE:
                return (int)$this->attributes['pain_point'] +
                    (int)$this->attributes['value_proposition'] +
                    (int)$this->attributes['market_analysis'] +
                    (int)$this->attributes['financial_model'] +
                    (int)$this->attributes['expertise'];
            case Competition::NAME_PRELIMINARY_STAGE:
                return (int)$this->attributes['target_market'] +
                    (int)$this->attributes['solution'] +
                    (int)$this->attributes['competitive_advantage'] +
                    (int)$this->attributes['team_board_adviser'] +
                    (int)$this->attributes['financial'] +
                    (int)$this->attributes['exit_opportunity'] +
                    (int)$this->attributes['strategic_value'] +
                    (int)$this->attributes['spoke_clearly'] +
                    (int)$this->attributes['attitude'] +
                    (int)$this->attributes['relate_to_audience'];
            case Competition::NAME_FINAL:
                $total = 0;
                for($i=0; $i<25; $i++) {
                    $total += (int)$this->attributes['final_' . $i];
                }

                return $total;
        }

        return 0;
    }

    public function getFinalSum1Attribute()
    {
        return $this->final_5 + $this->final_6 + $this->final_7;
    }

    public function getFinalSum2Attribute()
    {
        return $this->final_8 + $this->final_9 + $this->final_10;
    }

    public function getFinalSum3Attribute()
    {
        return $this->final_11 + $this->final_12 + $this->final_13;
    }

    public function getJudgeOrderAttribute()
    {
        return $this->company->judge_order;
    }

    public function getCreateDemandScoreAttribute()
    {
        if ($this->attributes['create_demand_score'] === null) {
            return 1;
        }

        return $this->attributes['create_demand_score'];
    }

    public function getFulfillDemandScoreAttribute()
    {
        if ($this->attributes['fulfill_demand_score'] === null) {
            return 1;
        }

        return $this->attributes['fulfill_demand_score'];
    }

    public function getManageTheMoneyScoreAttribute()
    {
        if ($this->attributes['manage_the_money_score'] === null) {
            return 1;
        }

        return $this->attributes['manage_the_money_score'];
    }

    public function getBuildTheTeamScoreAttribute()
    {
        if ($this->attributes['build_the_team_score'] === null) {
            return 1;
        }

        return $this->attributes['build_the_team_score'];
    }

    public function getDueAttribute()
    {
        return $this->due_at->diffForHumans(Carbon::now());
    }

    public function getDueAtAttribute()
    {
        switch ($this->company->competition->name) {
            case Competition::NAME_ONLINE:
                return $this->created_at->addDays(8)->hour(0)->minute(0)->second(0);
            default:
                return $this->company->competition->date->hour(18)->minute(30)->second(0);
        }
    }

    public function getIsDueAttribute()
    {
        return Carbon::now()->gt($this->due_at);
    }

    public static function boot()
    {
        parent::boot();

        parent::observe(ScoreObserver::class);
    }
}
