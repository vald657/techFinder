<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    protected $table = "intervention";
    protected $primaryKey = "code_int";
    public $incrementing = true;
    protected $keyType = "int";
    public $timestamps = true;
    protected $fillable = [
        "date_int",
        "note_int",
        "commentaire_int",
        "code_user_client",
        "code_user_techn",
        "code_comp"
    ];

    public function client()
    {
        return $this->belongsTo(Utilisateur::class, 'code_user_client', 'code_user');
    }

    public function technicien()
    {
        return $this->belongsTo(Utilisateur::class, 'code_user_techn', 'code_user');
    }

    public function competence()
    {
        return $this->belongsTo(Competence::class, 'code_comp', 'code_comp');
    }
}
