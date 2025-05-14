<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Group extends Model {
    use HasFactory;
    protected $fillable = ['user_id', 'name', 'description'];

    protected static function booted()
    {
        static::creating(function ($group) {
            $group->slug = Str::slug($group->name);
        });
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function leagues() {
        return $this->hasMany(League::class);
    }
}
