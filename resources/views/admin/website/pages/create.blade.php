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
                <a href="/admin/website/pages" class="hover:text-gray-600 dark:hover:text-gray-300">Page Content</a>
                <span>&rsaquo;</span>
                <span class="text-[#008060] font-extrabold">Add content_new</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add content_new</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Create a new page and its content.</p>
        </div>
        <a href="/admin/website/pages" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 px-4 py-2.5 rounded-md border border-gray-300 dark:border-gray-650 transition-all">
            &larr; Back to Pages
        </a>
    </div>

    <form onsubmit="handleSave(event)">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left: Main Fields -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Basic Information -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Basic Information</h2>
                    <div class="space-y-4">

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Title <span class="text-red-500">*</span></label>
                            <input type="text" id="page-title" required placeholder="e.g. Bespoke Learning"
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Content <span class="text-red-500">*</span></label>
                                <button type="button" class="text-xs font-semibold text-[#008060] hover:underline">+ Add accordion</button>
                            </div>
                            <textarea id="page-content-editor"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Menu title</label>
                                <input type="text" id="menu-title" placeholder="e.g. Bespoke Courses"
                                    class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Url <span class="text-red-500">*</span></label>
                                <input type="text" id="page-url" required placeholder="e.g. learning-solutions/bespoke-learning"
                                    class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                            </div>
                        </div>


                    </div>
                </div>

            </div>

            <!-- Right: Sidebar -->
            <div class="space-y-6">

                <!-- Publish Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Publish</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Status <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select id="page-status" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] appearance-none cursor-pointer">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3 pt-5 border-t border-gray-200 dark:border-gray-700 mt-5">
                        <a href="/admin/website/pages" class="flex-1 text-center px-4 py-2.5 text-sm font-semibold rounded-md border border-gray-300 dark:border-gray-650 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-750 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</a>
                        <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] rounded-md transition-colors cursor-pointer">Save</button>
                    </div>
                </div>

                <!-- Page Banner Upload -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-1">Page banner</h2>
                    <p class="text-xxs text-gray-400 dark:text-gray-500 mb-3">Upload a banner image for this page</p>
                    <div id="banner-drop" onclick="document.getElementById('page-banner').click()"
                        class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center cursor-pointer hover:border-[#008060] transition-colors">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Click to upload image</p>
                    </div>
                    <input type="file" id="page-banner" accept="image/*" class="hidden" onchange="previewImage(this)">
                    <img id="banner-preview" class="hidden mt-3 w-full rounded-lg object-contain max-h-32 bg-gray-50 dark:bg-gray-700 p-2" src="" alt="Banner Preview">
                </div>

                <!-- Organization & SEO -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Organization & SEO</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Parent Page</label>
                            <div class="relative">
                                <select id="parent-page" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] appearance-none cursor-pointer">
                                    <option value="0">-- None --</option>
                                    @foreach($parentPages as $p)
                                        <option value="{{ $p->id }}">{{ $p->title }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">SEO Title <span class="text-red-500">*</span></label>
                            <input type="text" id="seo-title" required placeholder="e.g. What is Bespoke Learning?"
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Meta Description <span class="text-red-500">*</span></label>
                            <textarea id="meta-desc" required rows="4" placeholder="Discover Bespoke Learning solutions..."
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors"></textarea>
                        </div>
                    </div>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.4/jodit.es2018.min.js"></script>
<script>
    // Jodit Content editor
    var joditEditor = Jodit.make('#page-content-editor', {
        height: 300,
        placeholder: 'Write the page content here...'
    });

    function previewImage(input) {
        const preview = document.getElementById('banner-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => { preview.src = e.target.result; preview.classList.remove('hidden'); };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function handleSave(e) {
        e.preventDefault();
        const title   = document.getElementById('page-title').value.trim();
        const menu    = document.getElementById('menu-title').value.trim();
        const url     = document.getElementById('page-url').value.trim();
        const status  = document.getElementById('page-status').value;
        const seo     = document.getElementById('seo-title').value.trim();
        const meta    = document.getElementById('meta-desc').value.trim();
        const parentPage = document.getElementById('parent-page').value;
        const bannerInput = document.getElementById('page-banner');

        // Get Jodit content
        const htmlContent = joditEditor.value;

        if (!title || !url || !seo || !meta) { alert('Please fill all required fields.'); return; }
        if (!htmlContent.trim()) { alert('Content cannot be empty.'); return; }

        const formData = new FormData();
        formData.append('title', title);
        formData.append('menu_title', menu);
        formData.append('url', url);
        formData.append('status', status);
        formData.append('seo_title', seo);
        formData.append('meta_description', meta);
        formData.append('parent_page_id', parentPage);
        formData.append('content', htmlContent);
        
        if (bannerInput.files && bannerInput.files[0]) {
            formData.append('page_banner', bannerInput.files[0]);
        }

        fetch('/admin/website/pages', {
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
                showToast('Page saved successfully!');
                setTimeout(() => { window.location.href = '/admin/website/pages'; }, 1000);
            } else {
                alert(data.error || 'Failed to save page.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred while saving the page.');
        });
    }

    function showToast(msg) {
        const t = document.getElementById('toast');
        document.getElementById('toast-message').innerText = msg;
        t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-0 opacity-100 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm';
        setTimeout(() => { t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm'; }, 3500);
    }
</script>
@endsection
