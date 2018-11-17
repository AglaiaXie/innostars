<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['filename', 'disk_name', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
