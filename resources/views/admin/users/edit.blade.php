@extends('admin.layout')

@push('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.4/jodit.es2018.min.css"/>
@endpush

@section('content')
<div class="w-full">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-1.5 text-xxs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1.5">
                <a href="/admin" class="hover:text-gray-600 dark:hover:text-gray-300">Admin</a>
                <span>&rsaquo;</span>
                <a href="/admin/users" class="hover:text-gray-600 dark:hover:text-gray-300">Users</a>
                <span>&rsaquo;</span>
                <span class="text-[#008060] font-extrabold">Edit User</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit User</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Update user account details for {{ $user->username }}.</p>
        </div>
        <a href="/admin/users" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 px-4 py-2.5 rounded-md border border-gray-300 dark:border-gray-655 transition-all">
            &larr; Back to Users
        </a>
    </div>

    <form onsubmit="handleUpdate(event)" id="user-form">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- ── Left: Main Fields ── -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Account Info -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Account Information</h2>
                    <div class="space-y-4">

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Username <span class="text-red-500">*</span></label>
                            <input type="text" id="user-name" required value="{{ $user->username }}" placeholder="e.g. johnsmith99"
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Password</label>
                            <div class="flex gap-2">
                                <input type="password" id="password" placeholder="Leave blank to keep current password"
                                    class="flex-1 text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                                <button type="button" onclick="generatePassword()"
                                    class="px-4 py-2 text-xs font-bold text-[#008060] border border-[#008060] rounded-md hover:bg-[#008060] hover:text-white transition-colors whitespace-nowrap">
                                    Generate
                                </button>
                            </div>
                            <p class="text-xxs text-gray-400 dark:text-gray-500 mt-1.5">
                                Leave blank to keep existing password. Min 7 chars &middot; 1 uppercase &middot; 1 number &middot; 1 special character
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Job Title <span class="text-red-500">*</span></label>
                            <input type="text" id="job-title" required value="{{ $user->details->job_title ?? '' }}" placeholder="e.g. Sales Manager"
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Calendar Link</label>
                            <input type="url" id="calendar-link" value="{{ $user->details->calendar_link ?? '' }}" placeholder="https://calendly.com/..."
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                        </div>

                    </div>
                </div>

                <!-- About User -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">About User</h2>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Bio / Description</label>
                        <textarea id="about-user-editor">{!! $user->details->bio ?? '' !!}</textarea>
                    </div>
                </div>

                <!-- Personal Details -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Personal Details</h2>
                    <div class="space-y-4">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">First Name</label>
                                <input type="text" id="first-name" value="{{ $user->details->first_name ?? '' }}" placeholder="First name"
                                    class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Last Name</label>
                                <input type="text" id="last-name" value="{{ $user->details->last_name ?? '' }}" placeholder="Last name"
                                    class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Email <span class="text-red-500">*</span></label>
                                <input type="email" id="email" required value="{{ $user->email }}" placeholder="user@londontfe.com"
                                    class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">WhatsApp <span class="text-red-500">*</span></label>
                                <input type="text" id="whatsapp" required value="{{ $user->details->whatsapp ?? '' }}" placeholder="WhatsApp number"
                                    class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Mobile</label>
                                <div class="flex">
                                    @php
                                        $pCode = $user->details->phone_code ?? '+44';
                                    @endphp
                                    <select id="mobile-code" class="w-28 text-sm bg-gray-100 dark:bg-gray-600 border border-r-0 border-gray-300 dark:border-gray-650 text-gray-900 dark:text-gray-200 rounded-l-md px-2 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060]">
                                        <option value="+44" {{ $pCode === '+44' ? 'selected' : '' }}>🇬🇧 +44</option>
                                        <option value="+1" {{ $pCode === '+1' ? 'selected' : '' }}>🇺🇸 +1</option>
                                        <option value="+971" {{ $pCode === '+971' ? 'selected' : '' }}>🇦🇪 +971</option>
                                    </select>
                                    <input type="tel" id="mobile" value="{{ $user->details->phone ?? '' }}" placeholder="Mobile number"
                                        class="flex-1 text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-r-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] transition-colors">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Office Number</label>
                                <div class="flex">
                                    @php
                                        $oCode = $user->details->contact_no_code ?? '+44';
                                    @endphp
                                    <select id="office-code" class="w-28 text-sm bg-gray-100 dark:bg-gray-600 border border-r-0 border-gray-300 dark:border-gray-655 text-gray-900 dark:text-gray-200 rounded-l-md px-2 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060]">
                                        <option value="+44" {{ $oCode === '+44' ? 'selected' : '' }}>🇬🇧 +44</option>
                                        <option value="+1" {{ $oCode === '+1' ? 'selected' : '' }}>🇺🇸 +1</option>
                                        <option value="+971" {{ $oCode === '+971' ? 'selected' : '' }}>🇦🇪 +971</option>
                                    </select>
                                    <input type="tel" id="office-number" value="{{ $user->details->contact_no ?? '' }}" placeholder="Office number"
                                        class="flex-1 text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-r-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] transition-colors">
                                </div>
                            </div>
                        </div>

                        <div>
                            @php
                                $sex = $user->details->sex ?? 'Not Disclose';
                            @endphp
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Gender</label>
                            <div class="flex flex-wrap gap-2">
                                <label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 hover:border-[#008060] hover:bg-[#008060]/5 transition-colors text-sm text-gray-700 dark:text-gray-300">
                                    <input type="radio" name="gender" value="Male" {{ $sex === 'Male' ? 'checked' : '' }} class="accent-[#008060] w-3.5 h-3.5"> Male
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 hover:border-[#008060] hover:bg-[#008060]/5 transition-colors text-sm text-gray-700 dark:text-gray-300">
                                    <input type="radio" name="gender" value="Female" {{ $sex === 'Female' ? 'checked' : '' }} class="accent-[#008060] w-3.5 h-3.5"> Female
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 hover:border-[#008060] hover:bg-[#008060]/5 transition-colors text-sm text-gray-700 dark:text-gray-300">
                                    <input type="radio" name="gender" value="Not Disclose" {{ $sex === 'Not Disclose' ? 'checked' : '' }} class="accent-[#008060] w-3.5 h-3.5"> Not Disclose
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 hover:border-[#008060] hover:bg-[#008060]/5 transition-colors text-sm text-gray-700 dark:text-gray-300">
                                    <input type="radio" name="gender" value="Other" {{ $sex === 'Other' ? 'checked' : '' }} class="accent-[#008060] w-3.5 h-3.5"> Other
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Location & Notes -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Location & Notes</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Address</label>
                            <textarea id="address" rows="3" placeholder="Full address..."
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors resize-none">{{ $user->details->address ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Notes</label>
                            <textarea id="notes" rows="3" placeholder="Any internal notes..."
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors resize-none">{{ $user->details->notes ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ── Right: Sidebar ── -->
            <div class="space-y-6">

                <!-- Publish Card (Save/Cancel) -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Publish</h2>
                    <div class="space-y-4">
                        <div>
                            @php
                                $status = $user->details->status ?? 'Active';
                            @endphp
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Status</label>
                            <div class="relative">
                                <select id="user-status" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-650 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] appearance-none cursor-pointer">
                                    <option value="Active" {{ $status === 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ $status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="Suspended" {{ $status === 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Created Date</label>
                            <input type="date" id="created-date" value="{{ $user->create_date ? \Carbon\Carbon::parse($user->create_date)->format('Y-m-d') : '' }}"
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] transition-colors">
                        </div>
                    </div>
                    <div class="flex gap-3 pt-5 border-t border-gray-200 dark:border-gray-700 mt-5">
                        <a href="/admin/users" class="flex-1 text-center px-4 py-2.5 text-sm font-semibold rounded-md border border-gray-300 dark:border-gray-650 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-750 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</a>
                        <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] rounded-md transition-colors cursor-pointer">Update</button>
                    </div>
                </div>

                <!-- User Type Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">User Type</h2>
                    <div class="space-y-3">
                        <div>
                            @php
                                $role = $user->details->role ?? '';
                            @endphp
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Role <span id="role-asterisk" class="text-red-500 {{ $user->is_admin_eligible ? '' : 'hidden' }}">*</span></label>
                            <div class="relative">
                                <select id="user-type" {{ $user->is_admin_eligible ? 'required' : '' }} class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-650 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] appearance-none cursor-pointer">
                                    <option value="">Select User Type</option>
                                    <option value="Marketing" {{ $role === 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                    <option value="Sales" {{ $role === 'Sales' ? 'selected' : '' }}>Sales</option>
                                    <option value="Course Editor" {{ $role === 'Course Editor' ? 'selected' : '' }}>Course Editor</option>
                                    <option value="Operation" {{ $role === 'Operation' ? 'selected' : '' }}>Operation</option>
                                    <option value="superadmin" {{ $role === 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            @php
                                $selectedCategoryIds = isset($user->details->category_ids) && $user->details->category_ids !== '' ? explode(',', $user->details->category_ids) : [];
                            @endphp
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Categories</label>
                            <div class="mb-2">
                                <input type="text" id="category-search" placeholder="Search categories..." oninput="filterCategoriesTable()"
                                    class="w-full text-xs bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-655 text-gray-900 dark:text-gray-200 rounded-md px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-[#008060] transition-colors">
                            </div>
                            <div class="border border-gray-250 dark:border-gray-700 rounded-md overflow-hidden bg-white dark:bg-gray-800">
                                <div class="max-h-60 overflow-y-auto">
                                    <table class="w-full text-left text-xs border-collapse">
                                        <thead class="bg-[#f6f6f7] dark:bg-gray-900/40 border-b border-gray-250 dark:border-gray-700 sticky top-0 z-10">
                                            <tr>
                                                <th class="px-4 py-2 w-12 text-center">
                                                    <input type="checkbox" id="select-all-categories" onchange="toggleAllCategories(this)" class="rounded border-gray-300 dark:border-gray-655 text-[#008060] focus:ring-[#008060] cursor-pointer">
                                                </th>
                                                <th class="px-4 py-2 font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Category Name</th>
                                            </tr>
                                        </thead>
                                        <tbody id="category-table-body" class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($categories as $cat)
                                                <tr class="category-row hover:bg-gray-50/50 dark:hover:bg-gray-900/10 transition-colors" data-name="{{ strtolower($cat->category_name) }}">
                                                    <td class="px-4 py-2 text-center">
                                                        <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}" 
                                                            @if(in_array($cat->id, $selectedCategoryIds)) checked @endif
                                                            class="category-checkbox rounded border-gray-300 dark:border-gray-655 text-[#008060] focus:ring-[#008060] cursor-pointer">
                                                    </td>
                                                    <td class="px-4 py-2 text-gray-800 dark:text-gray-300 font-medium">{{ $cat->category_name }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div>
                            @php
                                $country = $user->details->country_name ?? '';
                            @endphp
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Country <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select id="country" required class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-655 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] appearance-none cursor-pointer">
                                    <option value="">-- Select Country --</option>
                                    <option value="UNITED KINGDOM" {{ $country === 'UNITED KINGDOM' ? 'selected' : '' }}>UNITED KINGDOM</option>
                                    <option value="UNITED STATES" {{ $country === 'UNITED STATES' ? 'selected' : '' }}>UNITED STATES</option>
                                    <option value="UNITED ARAB EMIRATES" {{ $country === 'UNITED ARAB EMIRATES' ? 'selected' : '' }}>UNITED ARAB EMIRATES</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Short Order</label>
                            <input type="number" id="short-order" value="{{ $user->details->short_order ?? 0 }}" min="0" placeholder="e.g. 1"
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                        </div>

                        @php
                            $showProfile = $user->details->show_admin_profile ?? 0;
                        @endphp
                        <div class="flex items-center justify-between pt-1">
                            <div>
                                <p class="text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wide">Show Admin Profile</p>
                                <p class="text-xxs text-gray-400 dark:text-gray-500 mt-0.5">Display on public pages</p>
                            </div>
                            <button type="button" id="show-profile-toggle" role="switch" aria-checked="{{ $showProfile == 1 ? 'true' : 'false' }}"
                                onclick="toggleProfile()"
                                class="relative inline-flex h-6 w-11 items-center rounded-full {{ $showProfile == 1 ? 'bg-[#008060]' : 'bg-gray-300 dark:bg-gray-600' }} transition-colors focus:outline-none focus:ring-2 focus:ring-[#008060] focus:ring-offset-1">
                                <span id="show-profile-dot" class="inline-block h-4 w-4 {{ $showProfile == 1 ? 'translate-x-6' : 'translate-x-1' }} rounded-full bg-white shadow transition-transform duration-200"></span>
                            </button>
                            <input type="hidden" id="show-admin-profile" value="{{ $showProfile }}">
                        </div>

                        @php
                            $adminEligible = $user->is_admin_eligible ?? 0;
                        @endphp
                        <div class="flex items-center justify-between pt-1 border-t border-gray-250 dark:border-gray-700 mt-3 pt-3">
                            <div>
                                <p class="text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wide">Admin Login Eligible</p>
                                <p class="text-xxs text-gray-400 dark:text-gray-500 mt-0.5">Eligible for admin login</p>
                            </div>
                            <button type="button" id="admin-eligible-toggle" role="switch" aria-checked="{{ $adminEligible == 1 ? 'true' : 'false' }}"
                                onclick="toggleAdminEligible()"
                                class="relative inline-flex h-6 w-11 items-center rounded-full {{ $adminEligible == 1 ? 'bg-[#008060]' : 'bg-gray-300 dark:bg-gray-600' }} transition-colors focus:outline-none focus:ring-2 focus:ring-[#008060] focus:ring-offset-1">
                                <span id="admin-eligible-dot" class="inline-block h-4 w-4 {{ $adminEligible == 1 ? 'translate-x-6' : 'translate-x-1' }} rounded-full bg-white shadow transition-transform duration-200"></span>
                            </button>
                            <input type="hidden" id="is-admin-eligible" value="{{ $adminEligible }}">
                        </div>

                    </div>
                </div>

                <!-- Profile Photo -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">User Photo</h2>
                    <p class="text-xxs text-gray-400 dark:text-gray-500 mb-3">Max 157w × 157h pixels</p>
                    <div id="photo-drop" onclick="document.getElementById('user-photo').click()"
                        class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center cursor-pointer hover:border-[#008060] transition-colors">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Click to change photo</p>
                        <p class="text-xxs text-gray-400 dark:text-gray-500 mt-1">PNG, JPG</p>
                    </div>
                    <input type="file" id="user-photo" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                    
                    @php
                        $photoUrl = '';
                        if (!empty($user->details->image_name)) {
                            $photoUrl = str_starts_with($user->details->image_name, 'http') ? $user->details->image_name : Storage::disk('s3')->url($user->details->image_name);
                        }
                    @endphp
                    <img id="photo-preview" class="{{ empty($photoUrl) ? 'hidden' : '' }} mt-3 w-full rounded-lg object-cover max-h-40" src="{{ $photoUrl }}" alt="Preview">
                </div>

            </div>
        </div>
    </form>

</div>

<!-- Toast -->
<div id="toast" class="fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm">
    <div class="rounded-full p-1 bg-green-500 text-white">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    </div>
    <span id="toast-message" class="text-sm font-semibold">Updated!</span>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.4/jodit.es2018.min.js"></script>
<script>
    // Jodit About User editor
    var joditEditor = Jodit.make('#about-user-editor', {
        height: 250,
        placeholder: 'Write a short bio or description about this user...'
    });

    function filterCategoriesTable() {
        const searchVal = document.getElementById('category-search').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.category-row');
        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            if (name.includes(searchVal)) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    }

    function toggleAllCategories(selectAllCheckbox) {
        const checkboxes = document.querySelectorAll('.category-checkbox');
        checkboxes.forEach(cb => {
            const row = cb.closest('.category-row');
            if (row && !row.classList.contains('hidden')) {
                cb.checked = selectAllCheckbox.checked;
            }
        });
    }

    function toggleProfile() {
        const btn = document.getElementById('show-profile-toggle');
        const dot = document.getElementById('show-profile-dot');
        const input = document.getElementById('show-admin-profile');
        const isOn = btn.getAttribute('aria-checked') === 'true';
        btn.setAttribute('aria-checked', !isOn);
        btn.classList.toggle('bg-[#008060]', !isOn);
        btn.classList.toggle('bg-gray-300', isOn);
        btn.classList.toggle('dark:bg-gray-600', isOn);
        dot.classList.toggle('translate-x-6', !isOn);
        dot.classList.toggle('translate-x-1', isOn);
        input.value = isOn ? '0' : '1';
    }

    function toggleAdminEligible() {
        const btn = document.getElementById('admin-eligible-toggle');
        const dot = document.getElementById('admin-eligible-dot');
        const input = document.getElementById('is-admin-eligible');
        const isOn = btn.getAttribute('aria-checked') === 'true';
        btn.setAttribute('aria-checked', !isOn);
        btn.classList.toggle('bg-[#008060]', !isOn);
        btn.classList.toggle('bg-gray-300', isOn);
        btn.classList.toggle('dark:bg-gray-600', isOn);
        dot.classList.toggle('translate-x-6', !isOn);
        dot.classList.toggle('translate-x-1', isOn);
        
        const newValue = isOn ? '0' : '1';
        input.value = newValue;
        
        const roleSelect = document.getElementById('user-type');
        const roleAsterisk = document.getElementById('role-asterisk');
        if (newValue === '1') {
            roleSelect.setAttribute('required', 'required');
            roleAsterisk.classList.remove('hidden');
        } else {
            roleSelect.removeAttribute('required');
            roleAsterisk.classList.add('hidden');
        }
    }

    function previewPhoto(input) {
        const preview = document.getElementById('photo-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => { preview.src = e.target.result; preview.classList.remove('hidden'); };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function generatePassword() {
        const chars = 'abcdefghijklmnopqrstuvwxyz';
        const uppers = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        const nums = '0123456789';
        const specials = '!@#$%^&*()_+';
        let pass = uppers[Math.floor(Math.random()*uppers.length)]
                 + nums[Math.floor(Math.random()*nums.length)]
                 + specials[Math.floor(Math.random()*specials.length)];
        for (let i = 0; i < 6; i++) pass += chars[Math.floor(Math.random()*chars.length)];
        const el = document.getElementById('password');
        el.value = pass.split('').sort(() => 0.5 - Math.random()).join('');
        el.type = 'text';
        setTimeout(() => { el.type = 'password'; }, 2000);
    }

    function handleUpdate(e) {
        e.preventDefault();
        
        const username = document.getElementById('user-name').value.trim();
        const password = document.getElementById('password').value;
        const email = document.getElementById('email').value.trim();
        const whatsapp = document.getElementById('whatsapp').value.trim();
        const country = document.getElementById('country').value;
        const jobTitle = document.getElementById('job-title').value.trim();
        const role = document.getElementById('user-type').value;

        const isAdminEligible = document.getElementById('is-admin-eligible').value === '1';
        if ((isAdminEligible && !role) || !username || !email || !whatsapp || !country || !jobTitle) {
            alert('Please fill all required fields (marked with *).');
            return;
        }

        const formData = new FormData();
        formData.append('username', username);
        if (password) formData.append('password', password);
        formData.append('email', email);
        formData.append('whatsapp', whatsapp);
        formData.append('country', country);
        formData.append('job_title', jobTitle);
        formData.append('role', role);

        formData.append('first_name', document.getElementById('first-name').value.trim());
        formData.append('last_name', document.getElementById('last-name').value.trim());
        formData.append('calendar_link', document.getElementById('calendar-link').value.trim());
        formData.append('status', document.getElementById('user-status').value);
        formData.append('created_date', document.getElementById('created-date').value);
        
        const selectedCategories = [];
        document.querySelectorAll('.category-checkbox:checked').forEach(cb => {
            selectedCategories.push(cb.value);
        });
        formData.append('category_id', selectedCategories.join(','));

        formData.append('short_order', document.getElementById('short-order').value);
        formData.append('show_admin_profile', document.getElementById('show-admin-profile').value);
        formData.append('is_admin_eligible', document.getElementById('is-admin-eligible').value);

        formData.append('address', document.getElementById('address').value.trim());
        formData.append('notes', document.getElementById('notes').value.trim());
        formData.append('bio', joditEditor.value);

        const genderEl = document.querySelector('input[name="gender"]:checked');
        if (genderEl) formData.append('gender', genderEl.value);

        formData.append('phone', document.getElementById('mobile').value.trim());
        formData.append('phone_code', document.getElementById('mobile-code').value);
        formData.append('contact_no', document.getElementById('office-number').value.trim());
        formData.append('contact_no_code', document.getElementById('office-code').value);

        const photoInput = document.getElementById('user-photo');
        if (photoInput.files.length > 0) {
            formData.append('photo', photoInput.files[0]);
        }

        fetch('/admin/users/{{ $user->id }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('User updated successfully!');
                setTimeout(() => { window.location.href = '/admin/users'; }, 1000);
            } else {
                alert(data.error || 'Failed to update user.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred.');
        });
    }

    function showToast(msg) {
        const t = document.getElementById('toast');
        document.getElementById('toast-message').innerText = msg;
        t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-0 opacity-100 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm';
        setTimeout(() => {
            t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm';
        }, 3500);
    }
</script>
@endsection
