<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class BankAccount extends Model
{
    protected $table = 'rachunek';
    protected $primaryKey = 'acc_number';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
 
    protected $fillable = ['acc_number', 'client_nip', 'ZUS'];
 
    protected function casts(): array
    {
        return ['ZUS' => 'boolean'];
    }
 
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_nip', 'client_nip');
    }
}
