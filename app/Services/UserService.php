<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
  public function lockUser($userId)
  {
    return DB::transaction(function () use ($userId) {
      // Acquire a row-level lock
      $user = User::where('id', $userId)->lockForUpdate()->first();

      if ($user && !$user->locked) {
        $user->locked = true;
        $user->save();
      }

      return $user;
    });
  }

  public function unlockUser($userId)
  {
    return DB::transaction(function () use ($userId) {
      $user = User::where('id', $userId)->lockForUpdate()->first();

      if ($user && $user->locked) {
        $user->locked = false;
        $user->save();
      }

      return $user;
    });
  }
}
