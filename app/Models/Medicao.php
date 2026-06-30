<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicao extends Model
{
    use HasFactory;

    protected $fillable = [
        'convenio_id',
        'porcentagem_conclusao',
        'numero_medicao',
        'valor',
    ];

    public function convenio()
    {
        return $this->belongsTo(Convenio::class);
    }
}
