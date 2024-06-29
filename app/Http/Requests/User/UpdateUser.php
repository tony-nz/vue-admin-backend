<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    $user = $this->route('user');

    return [
      'name' => 'sometimes|required',
      'password' => 'nullable|confirmed|min:8',
      'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
      'avatar' => 'nullable',
      'locked' => 'nullable|boolean',
    ];
  }
}