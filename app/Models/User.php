<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes;
    
    use Notifiable;

    protected $dates = ['deleted_at']; 

    protected $fillable = [
        'name', 'email', 'mobile', 'gender', 'address', 'status', 'role_id', 'password',
    ];

    protected $guarded = [];
    
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id')
            ->withPivot('can_read', 'can_update', 'can_view', 'can_delete', 'all');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'role' => optional($this->role)->name,
            'permissions' => $this->role->permissions->map(function ($permission) {
                return [
                    'name' => $permission->name,
                    'can_read' => $permission->pivot->can_read ?? false,
                    'can_update' => $permission->pivot->can_update ?? false,
                    'can_view' => $permission->pivot->can_view ?? false,
                    'can_delete' => $permission->pivot->can_delete ?? false,
                    'all' => $permission->pivot->all ?? false,
                ];
            }),
        ];
    }
}
