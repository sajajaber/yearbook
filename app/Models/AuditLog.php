<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'ip_address',
    ];

    /* a static method belongs to the class itself, not on a specific object
       "a self-contained action you trigger directly on the class" */
    public static function record(string $action, $model): void
    {
        self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'entity_type' => get_class($model),
            'entity_id' => $model->id,
            'ip_address' => request()->ip(),
        ]);
    }
}
