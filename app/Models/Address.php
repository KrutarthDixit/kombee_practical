<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'parent_id'
    ];

    /**
     * Get all of the userProfiles for the Address
     *
     * @return HasMany
     */
    public function userProfiles(): HasMany
    {
        return $this->hasMany(UserProfile::class);
    }
}
