<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use SoftDeletes;

    protected $table = 'batch';

    public $fillable = [
        'teacher_id',
        'day',
        'time',
        'deleted_at',
        'updated_at',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_batch', 'batch_id', 'student_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
