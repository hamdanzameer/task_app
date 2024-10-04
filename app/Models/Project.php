<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\TaskProgress;
class Project extends Model
{
    use HasFactory;
    const NOT_STARTED=0;
    const PENDING=0;
    const COMPLETED=0;

    protected $guarded=[];

    public static function createSlug($name){
        $code=Str::random(10).time();
        $slug=Str::slug($name).'-'.$code;
        return $slug;
    }

    public function task_progress(){
        return $this->hasOne(TaskProgress::class,'projectId');
    }
    public function tasks(){
        return $this->hasMany(Task::class,'projectId');
    }
}
