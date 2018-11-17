<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InvestorProfile
 * @package App\Models
 * @property User $user
 */
class FinalForm extends Model
{
    protected $guarded = ['id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function pitch_deck()
    {
        return $this->belongsTo(File::class, 'pitch_deck_id');
    }

    public function flight_ticket_receipt()
    {
        return $this->belongsTo(File::class, 'flight_ticket_receipt_id');
    }
}
