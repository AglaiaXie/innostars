<?php namespace App\Models;

use Zizaco\Entrust\EntrustRole;

/**
 * Class Role
 *
 * @property string $name
 * @property string $display_name
 * @property string $description
 */
class Role extends EntrustRole
{
    protected $fillable = ['name', 'display_name'];
}
