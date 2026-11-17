<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
 
class Report extends Model
{
    protected $table = 'raport';
    protected $primaryKey = 'report_id';
    public $timestamps = false;
 
    protected $fillable = ['employee_id', 'client_nip', 'date', 'content', 'notes'];
 
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
 
    public function emails(): BelongsToMany
    {
        return $this->belongsToMany(ClientEmail::class, 'email_report', 'report_id', 'email_id');
    }
}
