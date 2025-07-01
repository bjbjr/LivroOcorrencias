<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'logo_path',
        'address',
        'contact_person',
        'contact_email',
        'contact_phone',
    ];

    /**
     * Get the contracts for the client.
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Get all of the service points for the client through its contracts.
     */
    public function servicePoints()
    {
        return $this->hasManyThrough(ServicePoint::class, Contract::class);
    }

    /**
     * Get the users associated with this client.
     * These are users directly linked to the client (e.g. client admin users if any, or app users linked to client directly).
     */
    public function users()
    {
        return $this->hasMany(User::class); // Users directly linked to a client
    }

    /**
     * Get the occurrences for the client.
     * This might need to be adjusted if occurrences are linked via service points or contracts.
     * For now, assuming occurrences have a direct client_id.
     */
    public function occurrences()
    {
        // If occurrences are linked to service_points, and service_points to contracts, and contracts to clients:
        // This would require a more complex relationship or a direct client_id on occurrences.
        // Let's assume occurrences will have client_id for now, as per original migration.
        return $this->hasMany(Occurrence::class);
    }
}
