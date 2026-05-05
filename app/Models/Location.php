<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';
    protected $primaryKey = 'location_id';
    public $timestamps = false;

    protected $fillable = ['location_name', 'location_description'];

    public function premises()
    {
        return $this->hasMany(Premises::class, 'location_id', 'location_id');
    }

    // In app/Models/Location.php
    public function hasPremisesWithHistory(): bool
    {
        foreach ($this->premises as $premise) {
            if ($premise->hasApplicationHistory()) {
                return true;
            }
        }
        return false;
    }

    public function getPremisesWithHistoryCount(): int
    {
        $count = 0;
        foreach ($this->premises as $premise) {
            if ($premise->hasApplicationHistory()) {
                $count++;
            }
        }
        return $count;
    }
}
