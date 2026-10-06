<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Agent = 'agent';
    case Viewer = 'viewer';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrador',
            self::Agent => 'Agente',
            self::Viewer => 'Solo lectura',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Admin => 'badge-primary',
            self::Agent => 'badge-info',
            self::Viewer => 'badge-secondary',
        };
    }
}
