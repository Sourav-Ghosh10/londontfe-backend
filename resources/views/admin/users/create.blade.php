@extends('admin.layout')

@push('head')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow { border-radius: 8px 8px 0 0; border-color: #d1d5db !important; background: #f6f6f7; font-family: inherit; padding: 8px 10px; }
    .dark .ql-toolbar.ql-snow { background: #374151; border-color: #4b5563 !important; }
    .ql-container.ql-snow { border-radius: 0 0 8px 8px; border-color: #d1d5db !important; font-size: 14px; font-family: inherit; background: #f6f6f7; }
    .dark .ql-container.ql-snow { background: #374151; border-color: #4b5563 !important; color: #e5e7eb; }
    .ql-editor { min-height: 180px; line-height: 1.7; }
    .ql-editor.ql-blank::before { color: #9ca3af; font-style: normal; }
    .dark .ql-toolbar .ql-stroke { stroke: #d1d5db; }
    .dark .ql-toolbar .ql-fill { fill: #d1d5db; }
    .dark .ql-toolbar .ql-picker { color: #d1d5db; }
    .dark .ql-toolbar .ql-picker-options { background: #374151; border-color: #4b5563; }
    .ql-snow .ql-active .ql-stroke, .ql-snow .ql-toolbar button:hover .ql-stroke { stroke: #008060 !important; }
    .ql-snow .ql-active .ql-fill { fill: #008060 !important; }
</style>
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
                <span class="text-[#008060] font-extrabold">Add User</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add User</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Create a new staff user account.</p>
        </div>
        <a href="/admin/users" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 px-4 py-2.5 rounded-md border border-gray-300 dark:border-gray-650 transition-all">
            &larr; Back to Users
        </a>
    </div>

    <form onsubmit="handleSave(event)">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- ── Left: Main Fields ── -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Account Info -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Account Information</h2>
                    <div class="space-y-4">

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Username <span class="text-red-500">*</span></label>
                            <input type="text" id="user-name" required placeholder="e.g. johnsmith99"
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Password <span class="text-red-500">*</span></label>
                            <div class="flex gap-2">
                                <input type="password" id="password" required placeholder="Min 7 chars, 1 uppercase, 1 number, 1 special"
                                    class="flex-1 text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                                <button type="button" onclick="generatePassword()"
                                    class="px-4 py-2 text-xs font-bold text-[#008060] border border-[#008060] rounded-md hover:bg-[#008060] hover:text-white transition-colors whitespace-nowrap">
                                    Generate
                                </button>
                            </div>
                            <p class="text-xxs text-gray-400 dark:text-gray-500 mt-1.5">
                                Min 7 chars &middot; 1 uppercase &middot; 1 number &middot; 1 special character &middot; Cannot contain username
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Job Title <span class="text-red-500">*</span></label>
                            <input type="text" id="job-title" required placeholder="e.g. Sales Manager"
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Calendar Link</label>
                            <input type="url" id="calendar-link" placeholder="https://calendly.com/..."
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                        </div>

                    </div>
                </div>

                <!-- About User -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">About User</h2>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Bio / Description</label>
                        <textarea id="about-user-hidden" class="hidden"></textarea>
                        <div id="about-user-editor" class="rounded-lg overflow-hidden border border-gray-300 dark:border-gray-600"></div>
                    </div>
                </div>

                <!-- Personal Details -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Personal Details</h2>
                    <div class="space-y-4">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">First Name</label>
                                <input type="text" id="first-name" placeholder="First name"
                                    class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Last Name</label>
                                <input type="text" id="last-name" placeholder="Last name"
                                    class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Email <span class="text-red-500">*</span></label>
                                <input type="email" id="email" required placeholder="user@londontfe.com"
                                    class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">WhatsApp <span class="text-red-500">*</span></label>
                                <input type="text" id="whatsapp" required placeholder="WhatsApp number"
                                    class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Mobile</label>
                                <div class="flex">
                                    <select class="w-28 text-sm bg-gray-100 dark:bg-gray-600 border border-r-0 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-l-md px-2 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060]">
                                        <option>🇬🇧 +44</option>
                                        <option>🇺🇸 +1</option>
                                        <option>🇦🇪 +971</option>
                                    </select>
                                    <input type="tel" id="mobile" placeholder="Mobile number"
                                        class="flex-1 text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-r-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] transition-colors">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Office Number</label>
                                <div class="flex">
                                    <select class="w-28 text-sm bg-gray-100 dark:bg-gray-600 border border-r-0 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-l-md px-2 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060]">
                                        <option>🇬🇧 +44</option>
                                        <option>🇺🇸 +1</option>
                                        <option>🇦🇪 +971</option>
                                    </select>
                                    <input type="tel" id="office-number" placeholder="Office number"
                                        class="flex-1 text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-r-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] transition-colors">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Gender</label>
                            <div class="flex flex-wrap gap-2">
                                <label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 hover:border-[#008060] hover:bg-[#008060]/5 transition-colors text-sm text-gray-700 dark:text-gray-300">
                                    <input type="radio" name="gender" value="Male" class="accent-[#008060] w-3.5 h-3.5"> Male
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 hover:border-[#008060] hover:bg-[#008060]/5 transition-colors text-sm text-gray-700 dark:text-gray-300">
                                    <input type="radio" name="gender" value="Female" class="accent-[#008060] w-3.5 h-3.5"> Female
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 hover:border-[#008060] hover:bg-[#008060]/5 transition-colors text-sm text-gray-700 dark:text-gray-300">
                                    <input type="radio" name="gender" value="Not Disclose" class="accent-[#008060] w-3.5 h-3.5"> Not Disclose
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 hover:border-[#008060] hover:bg-[#008060]/5 transition-colors text-sm text-gray-700 dark:text-gray-300">
                                    <input type="radio" name="gender" value="Other" class="accent-[#008060] w-3.5 h-3.5"> Other
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
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Notes</label>
                            <textarea id="notes" rows="3" placeholder="Any internal notes..."
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors resize-none"></textarea>
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
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Status</label>
                            <div class="relative">
                                <select id="user-status" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] appearance-none cursor-pointer">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Suspended">Suspended</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Created Date</label>
                            <input type="date" id="created-date"
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] transition-colors">
                        </div>
                    </div>
                    <div class="flex gap-3 pt-5 border-t border-gray-200 dark:border-gray-700 mt-5">
                        <a href="/admin/users" class="flex-1 text-center px-4 py-2.5 text-sm font-semibold rounded-md border border-gray-300 dark:border-gray-650 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-750 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</a>
                        <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] rounded-md transition-colors cursor-pointer">Save</button>
                    </div>
                </div>

                <!-- User Type Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">User Type</h2>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Role <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select id="user-type" required class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] appearance-none cursor-pointer">
                                    <option value="">Select User Type</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Course Editor">Course Editor</option>
                                    <option value="Operation">Operation</option>
                                    <option value="superadmin">Superadmin</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Category</label>
                            <div class="relative">
                                <select id="sub-category" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] appearance-none cursor-pointer">
                                    <option value="">Select categories</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Management">Management</option>
                                    <option value="Support">Support</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Country <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select id="country" required class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] appearance-none cursor-pointer">
                                    <option value="">-- Select Country --</option>
                                    <option value="UNITED KINGDOM">UNITED KINGDOM</option>
                                    <option value="UNITED STATES">UNITED STATES</option>
                                    <option value="UNITED ARAB EMIRATES">UNITED ARAB EMIRATES</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Short Order</label>
                            <input type="number" id="short-order" min="0" placeholder="e.g. 1"
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <div>
                                <p class="text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wide">Show Admin Profile</p>
                                <p class="text-xxs text-gray-400 dark:text-gray-500 mt-0.5">Display on public pages</p>
                            </div>
                            <button type="button" id="show-profile-toggle" role="switch" aria-checked="false"
                                onclick="toggleProfile()"
                                class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-300 dark:bg-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-[#008060] focus:ring-offset-1">
                                <span id="show-profile-dot" class="inline-block h-4 w-4 translate-x-1 rounded-full bg-white shadow transition-transform duration-200"></span>
                            </button>
                            <input type="hidden" id="show-admin-profile" value="0">
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
                        <p class="text-xs text-gray-500 dark:text-gray-400">Click to upload photo</p>
                        <p class="text-xxs text-gray-400 dark:text-gray-500 mt-1">PNG, JPG</p>
                    </div>
                    <input type="file" id="user-photo" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                    <img id="photo-preview" class="hidden mt-3 w-full rounded-lg object-cover max-h-40" src="" alt="Preview">
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
    <span id="toast-message" class="text-sm font-semibold">Saved!</span>
</div>

<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
    document.getElementById('created-date').valueAsDate = new Date();

    // Quill About User editor
    const aboutQuill = new Quill('#about-user-editor', {
        theme: 'snow',
        placeholder: 'Write a short bio or description about this user...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ]
        }
    });

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

    function handleSave(e) {
        e.preventDefault();
        const type     = document.getElementById('user-type').value;
        const name     = document.getElementById('user-name').value.trim();
        const password = document.getElementById('password').value;
        const email    = document.getElementById('email').value.trim();
        const whatsapp = document.getElementById('whatsapp').value.trim();
        const country  = document.getElementById('country').value;
        const jobTitle = document.getElementById('job-title').value.trim();

        // Sync Quill content
        document.getElementById('about-user-hidden').value = aboutQuill.root.innerHTML;

        if (!type || !name || !password || !email || !whatsapp || !country || !jobTitle) {
            alert('Please fill all required fields (marked with *).');
            return;
        }

        let staff = [];
        try { staff = JSON.parse(localStorage.getItem('londontfe_staff') || '[]'); } catch(err) {}
        const newId = staff.length ? Math.max(...staff.map(s => s.id)) + 1 : 1;
        staff.push({ id: newId, name, email, country, type });
        localStorage.setItem('londontfe_staff', JSON.stringify(staff));

        showToast('User saved successfully!');
        setTimeout(() => { window.location.href = '/admin/users'; }, 1000);
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