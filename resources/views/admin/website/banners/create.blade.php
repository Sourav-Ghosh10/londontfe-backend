@extends('admin.layout')

@section('content')
<div class="w-full">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-1.5 text-xxs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1.5">
                <a href="/admin" class="hover:text-gray-600 dark:hover:text-gray-300">Admin</a>
                <span>&rsaquo;</span>
                <a href="/admin/website/banners" class="hover:text-gray-600 dark:hover:text-gray-300">Home Page Banner</a>
                <span>&rsaquo;</span>
                <span class="text-[#008060] font-extrabold">Add Banner Slider</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add Banner Slider</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Create a new banner for the home page slider.</p>
        </div>
        <a href="/admin/website/banners" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 px-4 py-2.5 rounded-md border border-gray-300 dark:border-gray-650 transition-all">
            &larr; Back to Banners
        </a>
    </div>

    <form onsubmit="handleSave(event)">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left: Main Fields -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Banner Details -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Banner Details</h2>
                    <div class="space-y-4">

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Url</label>
                            <input type="url" id="banner-url" placeholder="https://www.londontfe.com/..."
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Sequence <span class="text-red-500">*</span></label>
                            <input type="number" id="banner-sequence" min="1" required placeholder="e.g. 1"
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Alt tag <span class="text-red-500">*</span></label>
                            <input type="text" id="alt-tag" required placeholder="e.g. ChatGPT Course Banner"
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
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
                                <select id="banner-status" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] appearance-none cursor-pointer">
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
                        <a href="/admin/website/banners" class="flex-1 text-center px-4 py-2.5 text-sm font-semibold rounded-md border border-gray-300 dark:border-gray-650 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-750 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</a>
                        <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] rounded-md transition-colors cursor-pointer">Save</button>
                    </div>
                </div>

                <!-- Desktop Banner Upload -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-1">Desktop Banner <span class="text-red-500">*</span></h2>
                    <p class="text-xxs text-gray-400 dark:text-gray-500 mb-3">(H497px X W1905px max 700kb)</p>
                    <div id="desktop-drop" onclick="document.getElementById('desktop-banner').click()"
                        class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center cursor-pointer hover:border-[#008060] transition-colors">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Click to upload image</p>
                    </div>
                    <input type="file" id="desktop-banner" accept="image/*" class="hidden" onchange="previewImage(this, 'desktop-preview')">
                    <img id="desktop-preview" class="hidden mt-3 w-full rounded-lg object-contain max-h-32 bg-gray-50 dark:bg-gray-700 p-2" src="" alt="Desktop Preview">
                </div>

                <!-- Mobile Banner Upload -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-1">Mobile Banner <span class="text-red-500">*</span></h2>
                    <p class="text-xxs text-gray-400 dark:text-gray-500 mb-3">(H583px X W375px max 110kb)</p>
                    <div id="mobile-drop" onclick="document.getElementById('mobile-banner').click()"
                        class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center cursor-pointer hover:border-[#008060] transition-colors">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Click to upload image</p>
                    </div>
                    <input type="file" id="mobile-banner" accept="image/*" class="hidden" onchange="previewImage(this, 'mobile-preview')">
                    <img id="mobile-preview" class="hidden mt-3 w-full rounded-lg object-contain max-h-32 bg-gray-50 dark:bg-gray-700 p-2" src="" alt="Mobile Preview">
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

<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => { preview.src = e.target.result; preview.classList.remove('hidden'); };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function handleSave(e) {
        e.preventDefault();
        const url      = document.getElementById('banner-url').value.trim();
        const sequence = parseInt(document.getElementById('banner-sequence').value) || 0;
        const alt      = document.getElementById('alt-tag').value.trim();
        const status   = document.getElementById('banner-status').value;
        const desktopInput = document.getElementById('desktop-banner');
        const mobileInput  = document.getElementById('mobile-banner');

        if (!sequence) { alert('Sequence is required.'); return; }
        if (!alt) { alert('Alt Tag is required.'); return; }
        if (!desktopInput.files || !desktopInput.files[0]) { alert('Please upload a Desktop Banner.'); return; }
        if (!mobileInput.files || !mobileInput.files[0]) { alert('Please upload a Mobile Banner.'); return; }

        const formData = new FormData();
        formData.append('alt_tag', alt);
        formData.append('sequence', sequence);
        formData.append('status', status);
        if (url) {
            formData.append('url', url);
        }
        formData.append('image', desktopInput.files[0]);
        formData.append('mobile_image', mobileInput.files[0]);

        fetch('/admin/website/banners', {
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
                showToast('Banner saved successfully!');
                setTimeout(() => { window.location.href = '/admin/website/banners'; }, 1000);
            } else {
                alert(data.error || 'Failed to save banner.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred while saving the banner.');
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
