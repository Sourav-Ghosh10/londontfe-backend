<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Support\Str;

class FixAboutUsData extends Command
{
    protected $signature = 'app:fix-about-us';
    protected $description = 'Imports About Us data properly, saves images to S3, and cleans up the database.';

    public function handle()
    {
        // 1. Fetch custom_profiles
        $customProfiles = DB::table('custom_profile')->get();
        $keptUserIds = [];

        foreach ($customProfiles as $cp) {
            $fName = trim($cp->f_name);
            $lName = trim($cp->l_name);
            $email = strtolower($fName . '.' . $lName . '@londontfe.com');
            $email = str_replace(' ', '', $email);

            if (empty($fName) && empty($lName)) {
                $email = 'unknown' . $cp->id . '@londontfe.com';
            }

            // Create or update User
            $user = User::where('email', $email)->first();
            if (!$user) {
                $user = new User();
                $user->email = $email;
                $user->fname = $fName;
                $user->lname = $lName;
                $user->username = explode('@', $email)[0] . rand(100, 999);
                $user->password = bcrypt('Password@123');
                $user->user_type = 2; // Staff
                $user->status = $cp->user_status;
                $user->create_date = $cp->created_on == '0000-00-00' ? now() : $cp->created_on;
                $user->calender_link = '';
                $user->save();
            }
            $keptUserIds[] = $user->id;

            // Details
            $details = UserDetail::where('user_id', $user->id)->first();
            if (!$details) {
                $details = new UserDetail();
                $details->user_id = $user->id;
                $details->first_name = $fName;
                $details->last_name = $lName;
                $details->status = $cp->user_status;
                $details->country = 0;
                $details->contact_no_code = '';
                $details->contact_no = '';
                $details->phone_code = '';
                $details->passport_no = '';
                $details->phone = '';
                $details->whatsapp = '';
                $details->address = '';
                $details->bio = '';
                $details->calendar_link = '';
                $details->sex = 'male';
                $details->class_code = '';
                $details->accounting_details = '';
                $details->image_name = '';
                $details->image_ext = '';
                $details->notes = '';
                $details->passport_image = '';
                $details->reward_point = 0;
                $details->company_id = 0;
                $details->category_ids = '';
                $details->role = '';
                $details->show_admin_profile = 0;
            }

            // Merge details
            $details->about_us_text = $cp->about_user;
            $details->about_us_user_type = $cp->user_type;
            $details->is_on_about_us = 1;
            
            if ($cp->short_order) {
                $details->short_order = $cp->short_order;
            }
            if ($cp->job_title) {
                $details->job_title = $cp->job_title;
            }

            // Upload image to S3
            if ($cp->user_image) {
                $remoteUrl = 'https://www.londontfe.com/crm/uploads/custom_profile/' . $cp->user_image;
                try {
                    $imageContents = file_get_contents($remoteUrl);
                    if ($imageContents) {
                        $s3Path = 'about_us/' . $cp->user_image;
                        Storage::disk('s3')->put($s3Path, $imageContents, 'public');
                        $details->about_us_image = $s3Path;
                        $this->info("Uploaded image to S3 for {$fName} {$lName}");
                    }
                } catch (\Exception $e) {
                    $this->error("Failed to download image for {$fName} {$lName}: " . $e->getMessage());
                    // use remote url directly as fallback
                    $details->about_us_image = $remoteUrl;
                }
            }

            $details->save();
            $this->info("Processed profile: {$fName} {$lName}");
        }

        // 2. Delete everyone else EXCEPT superadmins (user_type = 11) and the ones we just processed
        $deletedCount = User::whereNotIn('id', $keptUserIds)
            ->where('user_type', '!=', 11)
            ->delete();

        // Delete orphaned user_details
        DB::table('user_details')->whereNotIn('user_id', function($q) {
            $q->select('id')->from('user');
        })->delete();

        $this->info("Deleted {$deletedCount} other users to clean up the DB.");
    }
}
