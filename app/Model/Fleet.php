<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string fleet_hash
 * @property string fleet_data
 * @property boolean fleet_done
 */
class Fleet extends Model
{
    protected $primaryKey = 'fleet_hash';
    public $incrementing = false;
    protected $keyType = 'string';
}
