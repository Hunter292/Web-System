<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
 
class ClientEmail extends Model
{
    protected $table = 'email_client';
    protected $primaryKey = 'email_id';
    public $timestamps = false;
 
    protected $fillable = ['client_nip', 'email'];
 
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_nip', 'client_nip');
    }
 
    public function reports(): BelongsToMany
    {
        return $this->belongsToMany(Report::class, 'email_report', 'email_id', 'report_id');
    }
}
 
