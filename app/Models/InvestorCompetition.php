<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InvestorCompetition
 * @package App\Models
 * @property InvestorProfile $judge
 * @property Competition  $competition
 */
class InvestorCompetition extends Model
{
    protected $fillable = [
        'investor_profile_id',
        'competition_id',
    ];

    public $timestamps = false;

    public function investor()
    {
        return $this->belongsTo(InvestorProfile::class, 'investor_profile_id');
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
}
