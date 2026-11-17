<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
 
class Client extends Model
{
    protected $table = 'klient';
    protected $primaryKey = 'client_nip';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
 
    protected $fillable = ['client_nip', 'name'];
 
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'client_nip', 'client_nip');
    }
 
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'client_nip', 'client_nip');
    }
 
    public function emails(): HasMany
    {
        return $this->hasMany(ClientEmail::class, 'client_nip', 'client_nip');
    }
 
    public function works(): HasMany
    {
        return $this->hasMany(Work::class, 'client_nip', 'client_nip');
    }
 
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'client_nip', 'client_nip');
    }
 
    public function bankAccounts(): HasMany
    {
        return $this->hasMany(BankAccount::class, 'client_nip', 'client_nip');
    }
}
