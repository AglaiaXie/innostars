<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubIndustry extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }
}
