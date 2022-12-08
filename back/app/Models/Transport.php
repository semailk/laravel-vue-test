<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $status
 * @property string $user_id
 */
class Transport extends Model
{
    use HasFactory;

    const TRANSPORT_NAME = 'name';
    const TRANSPORT_STATUS = 'status';
    const TRANSPORT_USER_ID = 'user_id';
    const TRANSPORT_STATUS_ALL = [
        'ACTIVE', 'ON REPAIR', 'SOLD', 'NOT USED'
    ];

    protected $fillable = [
        self::TRANSPORT_NAME,
        self::TRANSPORT_STATUS,
        self::TRANSPORT_USER_ID,
    ];

}
