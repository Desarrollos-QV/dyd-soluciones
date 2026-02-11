<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Datos de usuario
        'name', 
        'email', 
        'telefono', 
        'password', 
        'role', 
        'parent_id', 
        'permission', 
        'permissions',
        'is_active',
    
        // Datos para Tecnico
        'avatar',
        'identificacion',
        'lastname',
        'schooling',
        'experience',
        'licence',
        'vehicle',
        'tools',
        'skills',
        'uniform',

        // Datos para vendedores
    ];

    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function serviciosAgendados()
    {
        return $this->hasMany(ServiciosAgendado::class);
    }

    public function solicitudes()
    {
        return $this->hasMany(SolicitudInstalacion::class);
    }

    public function otrosServicios()
    {
        return $this->hasMany(OtroServicio::class);
    }

    // Relación: Subcuentas de un administrador
    public function subaccounts()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    // Relación: Usuario padre (admin principal)
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSubadmin()
    {
        return $this->role === 'subadmin';
    }

    public function isTecnico()
    {
        return $this->role === 'tecnico';
    }

    public function isSeller()
    {
        return $this->role === 'seller';
    }

    /**
     * Get the user's is Super Admin.
     */
    public function isSuperAdmin()
    {
        if ($this->permissions == 'full_access') {
            return $this->id;
        }
        return false;
    }

    /**
     * Get the user's permissions.
     */
    public function hasPermission($section)
    {
        $permissions = explode(',', $this->permissions);

        return in_array($section, $permissions)
            || $this->permissions == 'full_access'
            || $this->permissions == 'limited_access'
            ? true
            : false;
    }


    public function setPermissions($data)
    {
        
        // dashboard
        // subaccounts
        // clientes
        // tecnicos
        // inventario
        // gastos
        // reports
        // servicios
        // reportes_services
        $permission = [];
        foreach ($data as $perms => $value) {
            switch ($perms) {
                case 'dashboard':
                    $permission[] = 'dashboard';
                    break;
                case 'subaccounts':
                    $permission[] = 'subaccounts.index';
                    $permission[] = 'subaccounts.create';
                    $permission[] = 'subaccounts.edit';
                    break;
                case 'clientes':
                    $permission[] = 'clientes.index';
                    $permission[] = 'clientes.create';
                    $permission[] = 'clientes.edit';
                    break;
                case 'tecnicos':
                    $permission[] = 'tecnicos.index';
                    $permission[] = 'tecnicos.create';
                    $permission[] = 'tecnicos.edit';
                    break;
                case 'inventario':
                    $permission[] = 'inventario.index';
                    $permission[] = 'inventario.create';
                    $permission[] = 'inventario.edit';
                    break;
                case 'gastos':
                    $permission[] = 'gastos.index';
                    $permission[] = 'gastos.create';
                    $permission[] = 'gastos.edit';
                    break;
                case 'reports':
                    $permission[] = 'reports.index';
                    $permission[] = 'reports.create';
                    $permission[] = 'reports.edit';
                    break;
                case 'servicios':
                    $permission[] = 'servicios.index';
                    $permission[] = 'servicios.create';
                    $permission[] = 'servicios.edit';
                    break;
                case 'reportes_services':
                    $permission[] = 'reportes_services.index';
                    $permission[] = 'reportes_services.create';
                    $permission[] = 'reportes_services.edit';
                    break;
            }
        }

        return $permission;
    }
    public function reportes()
    {
        return $this->hasMany(ReporteIngresoEgreso::class);
    }

}
