<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    /**
     * @return array<string, string>
     */
    public static function roleOptions(): array
    {
        return [
            'admin' => 'Administrator',
            'bendahara' => 'Bendahara',
            'panitia_tes' => 'Panitia tes',
            'operator_cetak' => 'Operator cetak',
        ];
    }

    public function roleLabel(): string
    {
        return self::roleOptions()[$this->role ?? 'admin'] ?? (string) $this->role;
    }

    public function canAccessAdminModule(string $module): bool
    {
        return match ($module) {
            'dashboard' => $this->hasRole('admin', 'bendahara', 'panitia_tes', 'operator_cetak'),
            'santris' => $this->hasRole('admin', 'bendahara', 'panitia_tes'),
            'payments' => $this->hasRole('admin', 'bendahara'),
            'tes' => $this->hasRole('admin', 'panitia_tes'),
            'certificates' => $this->hasRole('admin', 'operator_cetak'),
            'photo_sheets' => $this->hasRole('admin', 'operator_cetak'),
            'templates' => $this->hasRole('admin', 'operator_cetak'),
            'gelombangs' => $this->hasRole('admin', 'operator_cetak'),
            'settings' => $this->isAdmin(),
            'laporan' => $this->hasRole('admin', 'bendahara'),
            'vouchers' => $this->hasRole('admin', 'bendahara'),
            default => $this->isAdmin(),
        };
    }
}
