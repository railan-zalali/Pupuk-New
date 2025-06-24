<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Role extends Model
{


    protected $fillable = ['name', 'slug', 'description'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // Helper untuk mengecek permission
    public function hasPermission($permission)
    {
        return $this->permissions()->where('slug', $permission)->exists();
    }
}
