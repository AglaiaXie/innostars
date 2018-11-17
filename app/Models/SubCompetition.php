<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCompetition extends Model
{
    protected $fillable = ['city', 'start', 'end'];

    protected $dates = ['start', 'end'];

    public $timestamps = false;
}