<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Role extends \Spatie\Permission\Models\Role
{
  public function users(): MorphToMany
  {
    return $this->morphedByMany(
      User::class,
      'model',
      config('permission.table_names.model_has_roles'),
      'role_id',
      config('permission.column_names.model_morph_key')
    );
  }
}
