<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
 
class Task extends Model
{
    protected $table = 'zadanie';
    protected $primaryKey = 'task_id';
    public $timestamps = false;
 
    protected $fillable = ['date', 'deadline', 'employee_id', 'client_nip', 'description', 'priority'];
 
    protected function casts(): array
    {
        return [
            'date' => 'datetime',
            'deadline' => 'date',
            'priority' => 'integer',
        ];
    }
 
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
 
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_nip', 'client_nip');
    }
 
    public function workers(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'task_worker', 'task_id', 'employee_id')
            ->withPivot('status');
    }
}
