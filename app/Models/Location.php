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
}
