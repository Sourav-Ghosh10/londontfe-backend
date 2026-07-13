<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\UserDetail;
use Carbon\Carbon;

#[Signature('app:merge-custom-profile-data')]
#[Description('Merge custom profile data into user and user_details tables')]
class MergeCustomProfileData extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $customProfiles = DB::table('custom_profile')->get();
        $count = 0;

        foreach ($customProfiles as $cp) {
            $user = User::where('email', $cp->email)->first();

            if (!$user) {
                // Create user
                $user = new User();
                $user->email = $cp->email;
                $user->fname = $cp->f_name;
                $user->lname = $cp->l_name;
                $user->username = explode('@', $cp->email)[0] . rand(100, 999);
                $user->password = bcrypt('Password@123'); // Default password
                $user->user_type = 2; // Assuming 2 is a generic or admin role, or we can use generic
                $user->status = $cp->user_status;
                $user->create_date = $cp->created_on == '0000-00-00' ? now() : $cp->created_on;
                $user->calender_link = ''; // Added default empty string to avoid db constraint error
                $user->save();
                
                $this->info('Created new user for ' . $cp->email);
            }

            // check user details
            $details = UserDetail::where('user_id', $user->id)->first();
            if (!$details) {
                $details = new UserDetail();
                $details->user_id = $user->id;
                $details->first_name = $cp->f_name ?: '';
                $details->last_name = $cp->l_name ?: '';
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
            $details->about_us_image = $cp->user_image;
            $details->company_logo = $cp->user_com_logo;
            $details->about_us_user_type = $cp->user_type;
            $details->is_on_about_us = 1;
            
            if ($cp->short_order) {
                $details->short_order = $cp->short_order;
            }
            if ($cp->job_title && !$details->job_title) {
                $details->job_title = $cp->job_title;
            }

            $details->save();
            $count++;
        }

        $this->info("Successfully merged $count custom profiles.");
    }
}
