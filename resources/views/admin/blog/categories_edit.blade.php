@extends('admin.layout')

@section('content')
<div class="w-full">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-1.5 text-xxs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1.5">
                <a href="/admin" class="hover:text-gray-600 dark:hover:text-gray-300">Admin</a>
                <span>&rsaquo;</span>
                <a href="/admin/blog" class="hover:text-gray-600 dark:hover:text-gray-300">Blog</a>
                <span>&rsaquo;</span>
                <a href="/admin/blog/categories" class="hover:text-gray-600 dark:hover:text-gray-300">Categories</a>
                <span>&rsaquo;</span>
                <span class="text-[#008060] font-extrabold">Add Category</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Blog Category</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Edit an existing blog category.</p>
        </div>
        <a href="/admin/blog/categories" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 px-4 py-2.5 rounded-md border border-gray-300 dark:border-gray-650 transition-all">
            &larr; Back to Categories
        </a>
    </div>

    <!-- Form Card -->
    <div class="max-w-2xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Edit Blog Category</h2>
            </div>

            <form onsubmit="handleSave(event, false)" class="p-6 space-y-5">

                <div class="space-y-1.5">
                    <label for="cat-name" class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="cat-name"
                        required
                        oninput="autoSlug()"
                        value="{{ $category->category_name }}"
                        class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors"
                        placeholder="e.g. Project Management"
                    >
                    <p class="text-xxs text-gray-400 dark:text-gray-500 mt-1">
                        Slug: <span id="slug-preview" class="font-mono text-gray-500 dark:text-gray-400">{{ $category->blog_cate_slug }}</span>
                    </p>
                </div>

                <div class="space-y-1.5">
                    <label for="cat-title" class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="cat-title"
                        required
                        value="{{ $seo->title ?? '' }}"
                        class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors"
                        placeholder="Page title for this category"
                    >
                </div>

                <div class="space-y-1.5">
                    <label for="cat-meta" class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">
                        Meta Description <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="cat-meta"
                        rows="5"
                        required
                        class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors resize-none"
                        placeholder="SEO meta description for this category..."
                    >{{ $seo->meta_description ?? '' }}</textarea>
                </div>

                <div class="space-y-1.5">
                    <label for="cat-status" class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider">
                        Status
                    </label>
                    <div class="relative">
                        <select id="cat-status" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] appearance-none cursor-pointer">
                            <option value="Active" {{ in_array(strtolower($category->status), ['active', '1']) ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ in_array(strtolower($category->status), ['inactive', '0']) ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] rounded-md transition-colors cursor-pointer">
                        Save
                    </button>
                    <button type="button" onclick="handleSave(event, true)" class="px-5 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-750 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md border border-gray-300 dark:border-gray-655 transition-colors cursor-pointer">
                        Save and go back to list
                    </button>
                    <a href="/admin/blog/categories" class="px-5 py-2.5 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:white transition-colors cursor-pointer">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>

<!-- Toast -->
<div id="toast" class="fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm">
    <div class="rounded-full p-1 bg-green-500 text-white">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    </div>
    <span id="toast-message" class="text-sm font-semibold">Saved!</span>
</div>

<script>
    function autoSlug() {
        const name = document.getElementById("cat-name").value;
        const slug = name.toLowerCase().trim().replace(/[^a-z0-9]+/g, "-").replace(/^-+|-+$/g, "");
        document.getElementById("slug-preview").innerText = slug || "—";
    }

    async function handleSave(e, goBack) {
        e.preventDefault();
        const name = document.getElementById("cat-name").value.trim();
        const title = document.getElementById("cat-title").value.trim();
        const meta = document.getElementById("cat-meta").value.trim();
        const status = document.getElementById("cat-status").value;

        if (!name || !title || !meta) return;

        const slug = name.toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/^-+|-+$/g, "");

        try {
            const response = await fetch('/admin/blog/categories/{{ $category->id }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ name, slug, title, meta, status })
            });

            const data = await response.json();
            if (data.success) {
                showToast("Category updated successfully!");

                if (goBack) {
                    setTimeout(() => { window.location.href = "/admin/blog/categories"; }, 900);
                } else {
                    // Clear form for another entry
                    setTimeout(() => {
                        document.getElementById("cat-name").value = "";
                        document.getElementById("cat-title").value = "";
                        document.getElementById("cat-meta").value = "";
                        document.getElementById("slug-preview").innerText = "—";
                    }, 900);
                }
            }
        } catch (error) {
            console.error(error);
            alert("Failed to save category.");
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
