<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Lesson extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='lessons';
    protected $primaryKey = 'id';
    protected $fillable=['lesson_name','date_start','date_end'];
    
}
