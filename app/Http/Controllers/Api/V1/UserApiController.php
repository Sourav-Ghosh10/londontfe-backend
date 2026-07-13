<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserApiController extends Controller
{
    /**
     * Get the list of users for the About Us page.
     * Orders them by short_order ascending.
     */
    public function aboutUsProfiles(Request $request)
    {
        try {
            $users = User::select(
                    'user.id',
                    'user.username',
                    'user.email as user_email',
                    'user_details.first_name',
                    'user_details.last_name',
                    'user_details.job_title',
                    'user_details.about_us_user_type as role',
                    'user_details.about_us_text as bio',
                    'user_details.about_us_image as image',
                    'user_details.company_logo',
                    'user_details.short_order'
                )
                ->join('user_details', 'user.id', '=', 'user_details.user_id')
                ->where('user_details.is_on_about_us', 1)
                ->orderBy('user_details.short_order', 'asc')
                ->get();

            // Format image URLs
            $users->transform(function ($user) {
                $imageUrl = null;
                if (!empty($user->image)) {
                    $imageUrl = str_starts_with($user->image, 'http') ? $user->image : Storage::disk('s3')->url($user->image);
                }

                $companyLogoUrl = null;
                if (!empty($user->company_logo)) {
                    $companyLogoUrl = str_starts_with($user->company_logo, 'http') ? $user->company_logo : Storage::disk('s3')->url($user->company_logo);
                }

                $fullName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));

                return [
                    'id' => $user->id,
                    'name' => $fullName !== '' ? $fullName : $user->username,
                    'email' => $user->user_email,
                    'role' => $user->role ?: $user->job_title,
                    'bio' => $user->bio,
                    'image' => $imageUrl,
                    'company_logo' => $companyLogoUrl,
                    'short_order' => $user->short_order,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $users
            ], 200);

        } catch (\Exception $e) {
            \Log::error('AboutUs Profiles API Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching about us profiles.'
            ], 500);
        }
    }
}
