<?php

namespace SmartCms\Kit\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;

/**
 * Class Admin
 *
 * @property int $id The unique identifier for the model.
 * @property string $username The username of the admin.
 * @property string $email The email address of the admin.
 * @property string $password The hashed password of the admin.
 * @property string|null $remember_token The token used for "remember me" functionality.
 * @property \DateTime $created_at The date and time when the model was created.
 * @property \DateTime $updated_at The date and time when the model was last updated.
 */
class Admin extends User implements FilamentUser
{
    use HasFactory;
    use Notifiable;

    protected $hidden = ['password', 'remember_token'];

    protected $guarded = [];

    protected $casts = [
        'password' => 'hashed',
        'notifications' => 'array',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getNameAttribute(): string
    {
        return $this->username ?? 'Admin';
    }

    public function getTable()
    {
        return config('kit.admins_table_name');
    }
}
