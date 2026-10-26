<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
 
class Employee extends Authenticatable
{
    protected $table = 'pracownik';
    protected $primaryKey = 'employee_id';
 
    // Only an "updated" column exists, no created_at
    const CREATED_AT = null;
    const UPDATED_AT = 'updated';
 
    protected $fillable = ['name', 'email', 'password', 'admin'];
 
    protected $hidden = ['password'];
 
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'admin' => 'boolean',
            'updated' => 'datetime',
        ];
    }
 
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'employee_id', 'employee_id');
    }
 
    public function works(): HasMany
    {
        return $this->hasMany(Work::class, 'employee_id', 'employee_id');
    }
 
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'employee_id', 'employee_id');
    }
 
    public function assignedTasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_worker', 'employee_id', 'task_id')
            ->withPivot('status');
    }
}
