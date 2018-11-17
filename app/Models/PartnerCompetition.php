<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PartnerCompetition
 * @package App\Models
 * @property PartnerProfile $partner
 * @property Competition  $competition
 */
class PartnerCompetition extends Model
{
    protected $fillable = [
        'partner_profile_id',
        'competition_id',
    ];

    public $timestamps = false;

    public function partner()
    {
        return $this->belongsTo(PartnerProfile::class, 'partner_profile_id');
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
}
