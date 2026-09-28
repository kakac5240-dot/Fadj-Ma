<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineGroup extends Model
{
    protected $fillable = ['nom'];

    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
}