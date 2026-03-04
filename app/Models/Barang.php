<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $fillable = ['nama_barang', 'kategori_id', 'satuan_id', 'ukuran_id', 'total_qty', 'created_by', 'updated_by'];
    public $timestamps = true;

    // Relationships
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function ukuran()
    {
        return $this->belongsTo(Ukuran::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function detailRequests()
    {
        return $this->hasMany(DetailRequestPengambilan::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}