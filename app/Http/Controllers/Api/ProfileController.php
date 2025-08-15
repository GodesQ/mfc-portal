<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
  public function updateProfile(UpdateProfileRequest $request)
  {
    $user = $request->user();
    $data = $request->validated();

    $user->update($data);

    return response()->json([
      'status' => 'success',
      'message' => 'Profile updated successfully',
      'user' => $request->user()
    ]);
  }
}
