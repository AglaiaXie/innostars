<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Industry
 * @package App\Models
 * @property string $name
 * @property SubIndustry[] $sub_industries
 */
class Industry extends Model
{
    const NAME_HEALTHCARE = 'Healthcare and Biotechnology';
    const NAME_MANUFACTURING = 'Advanced Manufacturing';
    const NAME_ENV = 'Environmental Technology';
    const NAME_IT = 'Information and Communication Technology';
    const NAME_AI = 'Artificial Intelligence and Augmented/Virtual Reality';
    const NAME_ENERGY = 'Renewable Energy';
    const NAME_MATERIALS = 'New Materials';

    protected $fillable = [
        'name',
    ];

    public static $abbr = [
        self::NAME_AI => 'AI',
        self::NAME_ENERGY => 'NRG',
        self::NAME_ENV => 'ENV',
        self::NAME_HEALTHCARE => 'H&B',
        self::NAME_IT => 'IT',
        self::NAME_MANUFACTURING => 'MFR',
        self::NAME_MATERIALS => 'MATL',
    ];

    public $timestamps = false;

    protected $appends = ['abbr'];

    public function getAbbrAttribute()
    {
        if (array_key_exists('name', $this->attributes)) {
            return self::$abbr[$this->attributes['name']];
        }

        return '';
    }

    public function sub_industries()
    {
        return $this->hasMany(SubIndustry::class);
    }
}
