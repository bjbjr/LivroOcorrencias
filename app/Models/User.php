<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Se estiver usando Sanctum para APIs
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable // implements MustVerifyEmail // Breeze pode adicionar isso
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'approval_status',
        'client_id',
        'service_point_id',
        'cpf',             // Adicionado
        'phone',           // Adicionado
        'company_name',    // Adicionado
        'email_verified_at', // Adicionado para controle manual/Breeze
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 10+ has this by default
    ];

    /**
     * Get the client associated with the user.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the service point associated with the user.
     */
    public function servicePoint()
    {
        return $this->belongsTo(ServicePoint::class);
    }

    /**
     * Get the occurrences registered by the user.
     */
    public function occurrences()
    {
        return $this->hasMany(Occurrence::class);
    }

    // Helper methods
    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }

    public function isAppUser(): bool
    {
        return $this->type === 'app_user';
    }

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }
}
