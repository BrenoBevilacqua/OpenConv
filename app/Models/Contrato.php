<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;

    protected $fillable = [
        'convenio_id',
        'numero_contrato',
        'objeto',
        'empresa_contratada',
        'valor',
        'data_assinatura',
        'validade_inicio',
        'validade_fim'
    ];

    public function convenio()
    {
        return $this->belongsTo(Convenio::class);
    }
}
