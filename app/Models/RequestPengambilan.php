<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestPengambilan extends Model
{
    protected $table = 'request_pengambilan';
    protected $fillable = ['lantai_id', 'user_id', 'status_request', 'created_by', 'updated_by'];
    protected $casts = [
        'status_request' => 'boolean',
    ];
    public $timestamps = true;

    // Relationships
    public function lantai()
    {
        return $this->belongsTo(Lantai::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(DetailRequestPengambilan::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
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