<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Acao;

class Convenio extends Model
{
    protected $fillable = [
        'numero_convenio',
        'ano_convenio',
        'identificacao',
        'objeto',
        'fonte_recursos',
        'valor_repasse',
        'valor_contrapartida',
        'valor_total',
        'concedente',
        'parlamentar',
        'conta_vinculada',
        'natureza_despesa',
        'data_assinatura',
        'data_vigencia',
        'banco',
        'agencia',
    ];

    public function acoes(){
    return $this->hasMany(Acao::class);
    }

    public function acompanhamentos() {
        return $this->hasMany(Acompanhamento::class);
    }

    public function contratos(){
        return $this->hasMany(Contrato::class);
    }
    public function medicoes(){
        return $this->hasMany(Medicao::class);
    }
    public function termos(){
        return $this->hasMany(Termo::class);
    }

}
