<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        
        'photo_url',
        'photo',
        'description',
        'composition',
        'fabricant',
        'type_consommation',
        'date_expiration',
    ];

    public function group()
    {
        return $this->belongsTo(MedicineGroup::class, 'medicine_group_id');
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}