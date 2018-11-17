<?php namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'description',
        'address',
        'competition_id',
        'published'
    ];

    protected $appends = [
        'start_date',
        'end_date',
        'slot_total',
        'slot_empty',
        'slot_pending',
        'slot_confirmed',
        'user_total',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function time_slots()
    {
        return $this->hasMany(TimeSlot::class);
    }

    public function schedules()
    {
        return $this->hasManyThrough(Schedule::class, TimeSlot::class);
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function events()
    {
        return $this->belongsTo(Event::class);
    }

    public function getStartDateAttribute()
    {
        if ($date = $this->time_slots()->orderBy('start')->first()) {
            return $date->start;
        }

        return null;
    }

    public function getEndDateAttribute()
    {
        if ($date = $this->time_slots()->orderBy('end', 'desc')->first()) {
            return $date->end;
        }

        return null;
    }

    public function getSlotTotalAttribute()
    {
        return $this->time_slots()->count();
    }

    public function getSlotEmptyAttribute()
    {
        return $this->time_slots()->whereDoesntHave('schedules', function (Builder $builder) {
            $builder->whereIn('status', ['pending', 'confirmed']);
        })->count();
    }

    public function getSlotPendingAttribute()
    {
        return $this->time_slots()->whereHas('schedules', function (Builder $builder) {
            $builder->where('status', '=', 'pending');
        })->count();
    }

    public function getSlotConfirmedAttribute()
    {
        return $this->time_slots()->whereHas('schedules', function (Builder $builder) {
            $builder->where('status', '=', 'confirmed');
        })->count();
    }

    public function getUserTotalAttribute()
    {
        return $this->users()->count();
    }
}
