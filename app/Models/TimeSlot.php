<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TimeSlot
 * @package App\Models
 * @property Carbon $start
 * @property Carbon $end
 * @property Event $event
 * @property integer $table_number
 */
class TimeSlot extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'table_number',
        'period',
        'start',
        'end',
    ];

    protected $dates = ['start', 'end'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
