<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class Work extends Model
{
    protected $table = 'praca';
    protected $primaryKey = 'work_id';
    public $timestamps = false;
 
    protected $fillable = [
        'employee_id', 'client_nip', 'work_type',
        'date', 'time_start', 'time_finish', 'notes',
    ];
 
    protected function casts(): array
    {
        return ['date' => 'date'];
    }
 
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
 
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_nip', 'client_nip');
    }
}
