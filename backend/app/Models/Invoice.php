<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class Invoice extends Model
{
    protected $table = 'faktura';
    protected $primaryKey = 'faktura_id';
    public $timestamps = false;
 
    protected $fillable = ['client_nip', 'amount', 'date'];
 
    protected function casts(): array
    {
        return [
            'amount' => 'float',
            'date' => 'date',
        ];
    }
 
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_nip', 'client_nip');
    }
}
