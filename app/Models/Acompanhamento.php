<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acompanhamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'convenio_id',
        'status',
        'monitorado',
        'situacao', 
        'porcentagem_conclusao',
        'valor_liberado',
    ];

    public function convenio()
    {
        return $this->belongsTo(Convenio::class);
    }
}
