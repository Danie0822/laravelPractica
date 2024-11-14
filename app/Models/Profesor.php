<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Profesor extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = 'profesor';
    protected $fillable = ['nombre', 'apellido', 'email', 'telefono', 'especialidad', 'clave'];
}
