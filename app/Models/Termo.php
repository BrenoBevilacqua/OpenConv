<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Termo extends Model
{
    use HasFactory;

    protected $fillable = [
        'convenio_id',
        'numero_termo',
        'aditivo',
        'termo_data',
        'termo_valor',
    ];

    public function convenio(){
        return $this->belongsTo(Convenio::class);
    }
}
