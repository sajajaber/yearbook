<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['role_name', 'permissions'];
    // ONLY role_name and permissions can be set via Role::create([...])
    // id, created_at, updated_at are handled automatically
}
