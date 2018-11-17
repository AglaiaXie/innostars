<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InvestorProfile
 * @package App\Models
 * @property User $user
 */
class SemifinalForm extends Model
{
    protected $guarded = ['id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function executive_summary()
    {
        return $this->belongsTo(File::class, 'executive_summary_id');
    }

    public function pitch_deck()
    {
        return $this->belongsTo(File::class, 'pitch_deck_id');
    }

    public function passport()
    {
        return $this->belongsTo(File::class, 'passport_id');
    }

    public function visa()
    {
        return $this->belongsTo(File::class, 'visa_id');
    }

    public function registration_form()
    {
        return $this->belongsTo(File::class, 'registration_form_id');
    }

    public function consent_form()
    {
        return $this->belongsTo(File::class, 'consent_form_id');
    }

    public function flight_ticket_receipt()
    {
        return $this->belongsTo(File::class, 'flight_ticket_receipt_id');
    }

    public function semifinal_form_people()
    {
        return $this->hasMany(SemifinalFormPerson::class);
    }
}
