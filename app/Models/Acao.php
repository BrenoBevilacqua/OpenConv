<?php

namespace App\Models;

use App\Models\Convenio;

use Illuminate\Database\Eloquent\Model;

class Acao extends Model
{
    protected $table = 'acoes';

    protected $fillable = [
        'convenio_id',
        'tipo',
        'data_edicao',
        'observacao',
    ];

    public function convenio()
    {
        return $this->belongsTo(Convenio::class);
    }
}
