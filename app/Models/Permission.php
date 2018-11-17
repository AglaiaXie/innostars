<?php namespace App\Models;

use Zizaco\Entrust\EntrustPermission;

/**
 * Class Permission
 *
 * @property string $name
 * @property string $display_name
 * @property string $description
 */
class Permission extends EntrustPermission
{
    protected $fillable = ['name'];
}
