<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $fillable = ['nama_kategori', 'created_by', 'updated_by'];
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
