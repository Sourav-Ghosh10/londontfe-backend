@extends('admin.layout')

@push('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.4/jodit.es2018.min.css"/>
<style>
    #content-error { display: none; }
</style>
@endpush

@section('content')
<div class="w-full">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-1.5 text-xxs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1.5">
                <a href="/admin" class="hover:text-gray-600 dark:hover:text-gray-300">Admin</a>
                <span>&rsaquo;</span>
                <a href="/admin/blog" class="hover:text-gray-600 dark:hover:text-gray-300">Blog</a>
                <span>&rsaquo;</span>
                <span class="text-[#008060] font-extrabold">Edit Article</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Article</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Edit and update a blog article.</p>
        </div>
        <a href="/admin/blog" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 px-4 py-2.5 rounded-md border border-gray-300 dark:border-gray-650 transition-all">
            &larr; Back to Articles
        </a>
    </div>

    <form onsubmit="handleSave(event)">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Title -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Article Content</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Title <span class="text-red-500">*</span></label>
                            <input type="text" id="art-title" value="{{ $blog->blog_title }}" required class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors" placeholder="Enter article title...">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Excerpt / Summary</label>
                            <textarea id="art-excerpt" rows="3" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors resize-none" placeholder="Brief summary of the article..."></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Content <span class="text-red-500">*</span></label>
                            <textarea id="quill-editor">{!! e($blog->content) !!}</textarea>
                            <p id="content-error" class="text-xxs text-red-500 font-semibold mt-1">Content is required.</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sidebar Settings -->
            <div class="space-y-6">

                <!-- Publish -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Publish</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Status</label>
                            <div class="relative">
                                <select id="art-status" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] appearance-none cursor-pointer">
                                    <option value="Published">Published</option>
                                    <option value="Draft">Draft</option>
                                    <option value="Pending">Pending Approval</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Post Date</label>
                            <input type="date" id="art-date" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] transition-colors">
                        </div>
                    </div>
                    <div class="flex gap-3 pt-5 border-t border-gray-200 dark:border-gray-700 mt-5">
                        <a href="/admin/blog" class="flex-1 text-center px-4 py-2.5 text-sm font-semibold rounded-md border border-gray-300 dark:border-gray-650 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-750 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer">Cancel</a>
                        <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] rounded-md transition-colors cursor-pointer">Save</button>
                    </div>
                </div>

                <!-- Category -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Category</h2>
                    <div class="relative">
                        <select id="art-category" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] appearance-none cursor-pointer">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $blog->blog_category_id == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <!-- SEO -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">SEO Settings</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Meta Title</label>
                            <input type="text" id="art-meta-title" value="{{ $seo->title ?? '' }}" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors" placeholder="SEO meta title...">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Meta Description</label>
                            <textarea id="art-meta-desc" rows="3" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors resize-none" placeholder="SEO meta description...">{{ $seo->meta_description ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Featured Image</h2>
                    <div id="image-drop" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center cursor-pointer hover:border-[#008060] transition-colors" onclick="document.getElementById('art-image').click()">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Click to upload image</p>
                        <p class="text-xxs text-gray-400 dark:text-gray-500 mt-1">PNG, JPG up to 5MB</p>
                    </div>
                    <input type="file" id="art-image" accept="image/*" class="hidden" onchange="previewImage(this)">
                    <img id="image-preview" class="hidden mt-3 w-full rounded-lg object-cover max-h-40" src="" alt="Preview">
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
    // Set proper date and status from db
    document.getElementById("art-date").value = "{{ $blog->post_date ? date('Y-m-d', strtotime($blog->post_date)) : '' }}";
    document.getElementById("art-status").value = "{{ $blog->status }}";

    // Show image if available
    @if($blog->image)
        document.getElementById("image-preview").src = "{{ \Illuminate\Support\Str::startsWith($blog->image, ['http://', 'https://']) ? $blog->image : (file_exists(public_path('storage/' . $blog->image)) ? asset('storage/' . $blog->image) : Storage::disk('s3')->url($blog->image)) }}";
        document.getElementById("image-preview").classList.remove("hidden");
    @endif

    // Initialise Jodit editor
    var joditEditor = Jodit.make('#quill-editor', {
        height: 350,
        placeholder: 'Write article content here...'
    });

    function previewImage(input) {
        const preview = document.getElementById("image-preview");
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => { preview.src = e.target.result; preview.classList.remove("hidden"); };
            reader.readAsDataURL(input.files[0]);
        }
    }

    async function handleSave(e) {
        e.preventDefault();
        const title = document.getElementById("art-title").value.trim();
        if (!title) return;

        // Get Jodit content
        const contentHtml = joditEditor.value;
        if (!contentHtml.trim()) {
            document.getElementById("content-error").style.display = "block";
            return;
        }
        document.getElementById("content-error").style.display = "none";

        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('title', title);
        formData.append('category', document.getElementById("art-category").value);
        formData.append('date', document.getElementById("art-date").value);
        formData.append('status', document.getElementById("art-status").value);
        formData.append('content', contentHtml);
        formData.append('meta_title', document.getElementById("art-meta-title").value);
        formData.append('meta_desc', document.getElementById("art-meta-desc").value);

        const imageFile = document.getElementById('art-image').files[0];
        if (imageFile) {
            formData.append('image', imageFile);
        }

        try {
            const response = await fetch('/admin/blog/{{ $blog->id }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                showToast("Article saved successfully!");
                setTimeout(() => { window.location.href = "/admin/blog"; }, 1200);
            } else {
                alert("Failed to save article.");
            }
        } catch (error) {
            console.error(error);
            alert("Error saving article.");
        }
    }

    function showToast(msg) {
        const t = document.getElementById("toast");
        document.getElementById("toast-message").innerText = msg;
        t.className = "fixed bottom-5 right-5 z-50 transform translate-y-0 opacity-100 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm";
        setTimeout(() => { t.className = "fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm"; }, 3500);
    }
</script>
@endsection
