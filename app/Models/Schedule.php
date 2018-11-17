<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Schedule
 * @package App\Models
 *
 * @property string $status
 * @property string $topic
 * @property TimeSlot $time_slot
 */
class Schedule extends Model
{
    const CONFIRMED = 'confirmed';
    const PENDING = 'pending';
    const DENIED = 'denied';
    const CANCELED = 'canceled';
    const REQUESTED = 'requested';

    protected $fillable = [
        'time_slot_id',
        'topic',
        'status',
    ];

    public function time_slot()
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('status');
    }
}
