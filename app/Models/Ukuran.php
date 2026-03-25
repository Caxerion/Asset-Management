<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ukuran extends Model
{
    protected $table = 'ukuran';
    protected $fillable = ['nama_ukuran', 'created_by', 'updated_by'];
    public $timestamps = true;
    
    //Relationships
    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::Class, 'updated_by');
    }
}
