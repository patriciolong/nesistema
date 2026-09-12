<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginData extends Model
{
    use HasFactory;

    protected $table = 'login_data';
    protected $primaryKey = 'id_login';
    public $timestamps = false;

    protected $fillable = [
        'l_fecha_hora',
        'id_usuario',
    ];

    protected $casts = [
        'l_fecha_hora' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }
}
