<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $table = 'student';

    public $fillable = [
        'user_id',
        'name',
        'gender',
        'school_id',
        'deleted_at',
        'updated_at',
    ];

    public function batchs(): BelongsToMany
    {
        return $this->belongsToMany(Batch::class, 'student_batch', 'student_id', 'batch_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
