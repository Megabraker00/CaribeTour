<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $attributes = [
        'role' => 'viewer',
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
        'password' => 'hashed',
        'role' => UserRole::class,
    ];

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'created_user_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'created_user_id');
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    protected function name(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value), // accesor
            set: fn ($value) => strtolower($value) // mutador
        );
    }

    // Admin LTE

    public function adminlte_image()
    {
        return 'https://www.gravatar.com/avatar/'.md5(strtolower(trim($this->email))).'?s=300&d=mp';
    }

    public function adminlte_desc()
    {
        return $this->role?->label() ?? 'Usuario';
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function canWrite(): bool
    {
        return $this->role === UserRole::Admin || $this->role === UserRole::Agent;
    }

    public function isLastAdmin(): bool
    {
        if (!$this->isAdmin()) {
            return false;
        }

        return !static::query()
            ->where('role', UserRole::Admin)
            ->whereKeyNot($this->getKey())
            ->exists();
    }

    public function adminlte_profile_url()
    {
        return "profile/$this->id";
    }

    // end Admin LTE
}
