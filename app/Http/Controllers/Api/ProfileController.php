<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
  public function updateProfile(UpdateProfileRequest $request)
  {
    $user = $request->user();
    $data = $request->validated();

    // Handle avatar upload if present
    if ($request->hasFile('avatar')) {
      $file = $request->file('avatar');
      $file_name = $user->mfc_id_number . '.' . $file->getClientOriginalExtension();
      $file_path = "avatars/";

      Storage::disk('public')->putFileAs($file_path, $file, $file_name);
    }

    $data['avatar'] = $file_name ?? $user->avatar;

    $user->update($data);

    return response()->json([
      'status' => 'success',
      'message' => 'Profile updated successfully',
      'user' => $request->user()
    ]);
  }
}
