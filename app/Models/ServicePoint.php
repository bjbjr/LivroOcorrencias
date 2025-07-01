<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePoint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        // 'client_id', // Removido
        'contract_id', // Adicionado
        'name',
        'address',
        'latitude',    // Adicionado
        'longitude',   // Adicionado
        'internal_code',
    ];

    /**
     * Get the contract that owns the service point.
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Get the client through the contract.
     */
    public function client()
    {
        return $this->contract->client(); // Acessa o cliente através do contrato
    }

    /**
     * Get the users associated with this service point.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the occurrences for the service point.
     */
    public function occurrences()
    {
        return $this->hasMany(Occurrence::class);
    }
}
