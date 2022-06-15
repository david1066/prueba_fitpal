<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Schedule extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='schedules';
    protected $primaryKey = 'id';
    protected $fillable=['lesson_id','weekday','time_start','time_end'];

    
}
