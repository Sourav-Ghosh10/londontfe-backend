<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    private $countryMap = [
        'UNITED KINGDOM' => 223,
        'UNITED STATES' => 224,
        'UNITED ARAB EMIRATES' => 225,
        223 => 'UNITED KINGDOM',
        224 => 'UNITED STATES',
        225 => 'UNITED ARAB EMIRATES',
    ];

    private $roleMap = [
        5 => 'Marketing',
        6 => 'Sales',
        7 => 'Course Editor',
        9 => 'Operation',
        10 => 'superadmin',
        11 => 'superadmin',
    ];

    private $roleToTypeMap = [
        'Marketing' => 5,
        'Sales' => 6,
        'Course Editor' => 7,
        'Operation' => 9,
        'superadmin' => 11,
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 100);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 100;
        }

        $query = User::leftJoin('user_details', 'user.id', '=', 'user_details.user_id')
            ->select('user.*');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            
            $searchCountryIds = [];
            foreach ($this->countryMap as $key => $val) {
                if (is_string($key) && str_contains(strtolower($key), strtolower($search))) {
                    $searchCountryIds[] = $val;
                }
            }

            $searchUserTypes = [];
            foreach ($this->roleMap as $typeId => $roleName) {
                if (str_contains(strtolower($roleName), strtolower($search))) {
                    $searchUserTypes[] = $typeId;
                }
            }

            $query->where(function($q) use ($search, $searchCountryIds, $searchUserTypes) {
                $q->where('user.username', 'like', '%' . $search . '%')
                  ->orWhere('user.email', 'like', '%' . $search . '%')
                  ->orWhere('user.fname', 'like', '%' . $search . '%')
                  ->orWhere('user.lname', 'like', '%' . $search . '%')
                  ->orWhere('user_details.role', 'like', '%' . $search . '%')
                  ->orWhere('user_details.status', 'like', '%' . $search . '%');
                
                if (!empty($searchCountryIds)) {
                    $q->orWhereIn('user_details.country', $searchCountryIds);
                }

                if (!empty($searchUserTypes)) {
                    $q->orWhereIn('user.user_type', $searchUserTypes);
                }
                
                if (strtolower($search) === 'yes') {
                    $q->orWhere('user.is_admin_eligible', 1);
                } elseif (strtolower($search) === 'no') {
                    $q->orWhere('user.is_admin_eligible', 0);
                }
            });
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'id');
        $sortDir = $request->input('sort_dir', 'desc');
        
        $allowedSorts = [
            'id' => 'user.id',
            'name' => 'user.fname',
            'email' => 'user.email',
            'country' => 'user_details.country',
            'type' => 'user_details.role',
            'is_admin_eligible' => 'user.is_admin_eligible'
        ];
        
        $sortColumn = isset($allowedSorts[$sortBy]) ? $allowedSorts[$sortBy] : 'user.id';
        $query->orderBy($sortColumn, $sortDir === 'asc' ? 'asc' : 'desc');

        $users = $query->with('details')->paginate($perPage)->withQueryString();

        $users->getCollection()->transform(function($user) {
            $details = $user->details;
            $countryId = $details ? $details->country : 223;
            $countryName = isset($this->countryMap[$countryId]) ? $this->countryMap[$countryId] : 'UNITED KINGDOM';
            $roleName = $details && $details->role ? $details->role : (isset($this->roleMap[$user->user_type]) ? $this->roleMap[$user->user_type] : 'None');
            return [
                'id' => $user->id,
                'name' => trim(($user->fname ?? '') . ' ' . ($user->lname ?? '')) ?: $user->username,
                'email' => $user->email,
                'country' => $countryName,
                'type' => $roleName,
                'status' => $details && $details->status ? $details->status : ($user->status == 1 ? 'Active' : 'Inactive'),
                'is_admin_eligible' => $user->is_admin_eligible == 1 ? 'Yes' : 'No',
            ];
        });

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = DB::table('category')
            ->select('id', 'category_name')
            ->where('status', 'active')
            ->orderBy('category_name', 'asc')
            ->get();
        return view('admin.users.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:user,username|max:255',
            'email' => 'required|email|unique:user,email|max:255',
            'password' => 'required|string|min:7',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'country' => 'required|string',
            'role' => $request->is_admin_eligible == 1 ? 'required|string' : 'nullable|string',
            'job_title' => 'required|string|max:255',
            'calendar_link' => 'nullable|url|max:255',
            'status' => 'required|string',
            'category_id' => 'nullable|string',
            'show_admin_profile' => 'required|integer|in:0,1',
            'is_admin_eligible' => 'required|integer|in:0,1',
            'short_order' => 'nullable|integer',
            'notes' => 'nullable|string',
            'address' => 'nullable|string',
            'bio' => 'nullable|string',
            'gender' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
            'phone_code' => 'nullable|string',
            'contact_no' => 'nullable|string|max:255',
            'contact_no_code' => 'nullable|string',
            'photo' => 'nullable|image|max:10240',
            'created_date' => 'nullable|date',
        ]);

        $photoPath = null;
        $photoExt = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->storePublicly('users', 's3');
            if (!$photoPath) {
                return response()->json(['success' => false, 'error' => 'Failed to upload photo to S3.'], 500);
            }
            $photoExt = $request->file('photo')->getClientOriginalExtension();
        }

        DB::transaction(function () use ($request, $photoPath, $photoExt) {
            $typeId = isset($this->roleToTypeMap[$request->role]) ? $this->roleToTypeMap[$request->role] : 3;

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin_eligible' => (int) $request->is_admin_eligible,
                'fname' => $request->first_name ?? '',
                'lname' => $request->last_name ?? '',
                'address' => $request->address ?? '',
                'whats' => $request->whatsapp ?? '',
                'calender_link' => $request->calendar_link ?? '',
                'user_type' => $typeId,
                'status' => $request->status === 'Active' ? '1' : '0',
                'create_date' => $request->created_date ? \Carbon\Carbon::parse($request->created_date)->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
                'title' => $request->job_title ?? '',
                'changed_status' => '0',
                'conversation_message' => null,
                'conversation_date' => null,
            ]);

            $countryId = isset($this->countryMap[$request->country]) ? $this->countryMap[$request->country] : 223;

            UserDetail::create([
                'user_id' => $user->id,
                'company_id' => 0,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'whatsapp' => $request->whatsapp,
                'address' => $request->address,
                'bio' => $request->bio,
                'status' => $request->status,
                'role' => $request->role,
                'calendar_link' => $request->calendar_link,
                'short_order' => $request->short_order ?? 0,
                'show_admin_profile' => (int) $request->show_admin_profile,
                'image_name' => $photoPath ?? '',
                'image_ext' => $photoExt,
                'country' => $countryId,
                'phone_code' => $request->phone_code ?? '',
                'phone' => $request->phone ?? '',
                'contact_no_code' => $request->contact_no_code ?? '',
                'contact_no' => $request->contact_no ?? '',
                'sex' => strtolower($request->gender ?? 'Not Disclose'),
                'notes' => $request->notes ?? '',
                'job_title' => $request->job_title,
                'category_ids' => $request->category_id ?? '',
                'passport_no' => '',
                'reward_point' => 0,
                'passport_image' => '',
            ]);
        });

        return response()->json(['success' => true]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::with('details')->findOrFail($id);
        if ($user->details) {
            $user->details->country_name = isset($this->countryMap[$user->details->country]) ? $this->countryMap[$user->details->country] : 'UNITED KINGDOM';
            if (empty($user->details->role)) {
                $user->details->role = isset($this->roleMap[$user->user_type]) ? $this->roleMap[$user->user_type] : 'superadmin';
            }
        } else {
            $user->setRelation('details', new UserDetail([
                'role' => isset($this->roleMap[$user->user_type]) ? $this->roleMap[$user->user_type] : 'superadmin',
                'country' => 223,
                'status' => 'Active'
            ]));
        }
        $categories = DB::table('category')
            ->select('id', 'category_name')
            ->where('status', 'active')
            ->orderBy('category_name', 'asc')
            ->get();
        return view('admin.users.edit', compact('user', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $details = UserDetail::where('user_id', $id)->first();

        $request->validate([
            'username' => 'required|string|unique:user,username,' . $id . '|max:255',
            'email' => 'required|email|unique:user,email,' . $id . '|max:255',
            'password' => 'nullable|string|min:7',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'country' => 'required|string',
            'role' => $request->is_admin_eligible == 1 ? 'required|string' : 'nullable|string',
            'job_title' => 'required|string|max:255',
            'calendar_link' => 'nullable|url|max:255',
            'status' => 'required|string',
            'category_id' => 'nullable|string',
            'show_admin_profile' => 'required|integer|in:0,1',
            'is_admin_eligible' => 'required|integer|in:0,1',
            'short_order' => 'nullable|integer',
            'notes' => 'nullable|string',
            'address' => 'nullable|string',
            'bio' => 'nullable|string',
            'gender' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
            'phone_code' => 'nullable|string',
            'contact_no' => 'nullable|string|max:255',
            'contact_no_code' => 'nullable|string',
            'photo' => 'nullable|image|max:10240',
            'created_date' => 'nullable|date',
        ]);

        $photoPath = $details->image_name ?? null;
        $photoExt = $details->image_ext ?? null;
        if ($request->hasFile('photo')) {
            if ($details && $details->image_name && !str_starts_with($details->image_name, 'http')) {
                Storage::disk('s3')->delete($details->image_name);
            }
            $photoPath = $request->file('photo')->storePublicly('users', 's3');
            if (!$photoPath) {
                return response()->json(['success' => false, 'error' => 'Failed to upload photo to S3.'], 500);
            }
            $photoExt = $request->file('photo')->getClientOriginalExtension();
        }

        DB::transaction(function () use ($request, $user, $details, $photoPath, $photoExt) {
            $typeId = isset($this->roleToTypeMap[$request->role]) ? $this->roleToTypeMap[$request->role] : 3;

            $userUpdate = [
                'username' => $request->username,
                'email' => $request->email,
                'is_admin_eligible' => (int) $request->is_admin_eligible,
                'fname' => $request->first_name ?? '',
                'lname' => $request->last_name ?? '',
                'address' => $request->address ?? '',
                'whats' => $request->whatsapp ?? '',
                'calender_link' => $request->calendar_link ?? '',
                'status' => $request->status === 'Active' ? '1' : '0',
                'title' => $request->job_title ?? '',
                'user_type' => $typeId,
                'create_date' => $request->created_date ? \Carbon\Carbon::parse($request->created_date)->format('Y-m-d H:i:s') : $user->create_date,
            ];
            if ($request->filled('password')) {
                $userUpdate['password'] = Hash::make($request->password);
            }
            $user->update($userUpdate);

            $countryId = isset($this->countryMap[$request->country]) ? $this->countryMap[$request->country] : 223;

            $detailData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'whatsapp' => $request->whatsapp,
                'address' => $request->address,
                'bio' => $request->bio,
                'status' => $request->status,
                'role' => $request->role,
                'calendar_link' => $request->calendar_link,
                'short_order' => $request->short_order ?? 0,
                'show_admin_profile' => (int) $request->show_admin_profile,
                'image_name' => $photoPath ?? '',
                'image_ext' => $photoExt,
                'country' => $countryId,
                'phone_code' => $request->phone_code ?? '',
                'phone' => $request->phone ?? '',
                'contact_no_code' => $request->contact_no_code ?? '',
                'contact_no' => $request->contact_no ?? '',
                'sex' => strtolower($request->gender ?? 'Not Disclose'),
                'notes' => $request->notes ?? '',
                'job_title' => $request->job_title,
                'category_ids' => $request->category_id ?? '',
                'passport_no' => '',
                'reward_point' => 0,
                'passport_image' => '',
            ];

            if ($details) {
                $details->update($detailData);
            } else {
                $detailData['user_id'] = $user->id;
                $detailData['company_id'] = 0;
                UserDetail::create($detailData);
            }
        });

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $details = UserDetail::where('user_id', $id)->first();

        if ($details && $details->image_name && !str_starts_with($details->image_name, 'http')) {
            Storage::disk('s3')->delete($details->image_name);
        }

        DB::transaction(function () use ($user, $details) {
            if ($details) {
                $details->delete();
            }
            $user->delete();
        });

        return response()->json(['success' => true]);
    }
}
