<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InvestorProfile
 * @package App\Models
 * @property User $user
 */
class SemifinalFormPerson extends Model
{
    protected $fillable = ['name', 'passport_id'];

    public $timestamps = false;

    public function semifinal_form()
    {
        return $this->belongsTo(SemifinalForm::class);
    }

    public function passport()
    {
        return $this->belongsTo(File::class, 'passport_id');
    }
}
