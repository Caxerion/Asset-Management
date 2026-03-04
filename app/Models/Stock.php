<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stock';
    protected $fillable = ['barang_id', 'request_pengambilan_id', 'harga', 'qty', 'created_by', 'updated_by'];
    public $timestamps = true;

    // Relationships
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function requestPengambilan()
    {
        return $this->belongsTo(RequestPengambilan::class, 'request_pengambilan_id');
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