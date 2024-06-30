<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
  use HasApiTokens, HasFactory, HasRoles, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'active',
    'name',
    'email',
    'avatar',
    'password',
    'locked',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'active' => 'boolean',
    'locked' => 'boolean',
    'email_verified_at' => 'datetime',
  ];

  /**
   * The attributes that should be appended to arrays.
   * 
   * @var array
   */
  protected $appends = ['role_ids'];

  public function getRoleIdsAttribute()
  {
    return $this->roles->pluck('id');
  }

  public function getPermissions()
  {
    return $this->getAllPermissions()->pluck('name');
  }

  public function getRoles()
  {
    return $this->getRoleNames();
  }
}