<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    protected function permissions(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->userPermissions(),
        )->shouldCache();
    }

    public function hasPermission(...$permissions)
    {
        foreach ($permissions as $permission) {
            $this->permissions->contains();
        }
    }

    // This can be cached, but not in the scope for now
    public function userPermissions(): array
    {
        $permissions = [];
        $this->roles->loadMissing('permissions');

        $this->roles->each(function(Role $role) use (&$permissions) {
            $role->permissions->each(function($permission) use (&$permissions) {
                $permissions[] = $permission;
            });
        });

        return array_values(array_unique($permissions));
    }

}
