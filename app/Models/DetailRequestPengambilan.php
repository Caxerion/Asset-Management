<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailRequestPengambilan extends Model
{
    protected $table = 'detail_request_pengambilan';
    protected $fillable = ['request_pengambilan_id', 'barang_id', 'qty', 'note', 'created_by', 'updated_by'];
    public $timestamps = true;

    // Relationships
    public function requestPengambilan()
    {
        return $this->belongsTo(RequestPengambilan::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
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