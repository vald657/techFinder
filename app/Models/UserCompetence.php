<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserCompetence extends Model
{
    use hasFactory;
    protected $table = "user_competence";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        "code_user",
        "code_comp"
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'code_user', 'code_user');
    }

    public function competence()
    {
        return $this->belongsTo(Competence::class, 'code_comp', 'code_comp');
    }
}
