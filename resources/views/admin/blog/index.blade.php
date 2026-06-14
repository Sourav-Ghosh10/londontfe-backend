@extends('admin.layout')

@section('content')
<div class="w-full">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-1.5 text-xxs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1.5">
                <a href="/admin" class="hover:text-gray-600 dark:hover:text-gray-300">Admin</a>
                <span>&rsaquo;</span>
                <span class="text-[#008060] font-extrabold">Blog</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Blog Management</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Manage all published and draft blog articles.</p>
        </div>
        <div>
            <a href="/admin/blog/create" class="inline-flex items-center justify-center text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] px-5 py-2.5 rounded-md transition-all shadow-xs focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] whitespace-nowrap">
                + New Article
            </a>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="/admin/blog" id="filter-form">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-5 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Status</label>
                    <div class="relative">
                        <select name="status" id="status-filter" onchange="this.form.submit()" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-750 border border-gray-300 dark:border-gray-655 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] appearance-none cursor-pointer">
                            <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>All Status</option>
                            <option value="Published" {{ request('status') === 'Published' ? 'selected' : '' }}>Published</option>
                            <option value="Draft" {{ request('status') === 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending Approval</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Category</label>
                    <div class="relative">
                        <select name="category" id="cat-filter" onchange="this.form.submit()" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-750 border border-gray-300 dark:border-gray-655 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] appearance-none cursor-pointer">
                            <option value="all" {{ request('category') === 'all' || !request('category') ? 'selected' : '' }}>All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Search</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-450 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-4.35-4.35"/></svg>
                        </span>
                        <input type="text" name="search" id="blog-search" value="{{ request('search') }}" placeholder="Search by title or author..." class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-750 border border-gray-300 dark:border-gray-655 text-gray-900 dark:text-gray-200 rounded-md pl-10 pr-10 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] transition-colors">
                        @if(request('search'))
                        <a href="/admin/blog?status={{ request('status', 'all') }}&category={{ request('category', 'all') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-450 hover:text-gray-655">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xxs font-bold text-gray-700 dark:text-gray-400 bg-[#f6f6f7] dark:bg-gray-900/40 uppercase border-b border-gray-250 dark:border-gray-700">
                    <tr>
                        <th class="px-5 py-4">Title</th>
                        <th class="px-5 py-4">Category</th>
                        <th class="px-5 py-4">Author</th>
                        <th class="px-5 py-4">Post Date</th>
                        <th class="px-5 py-4 text-center">Status</th>
                        <th class="px-5 py-4 text-right w-32">Actions</th>
                    </tr>
                </thead>
                <tbody id="blog-table-body" class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($blogs as $blog)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/10 transition-colors text-xs text-gray-800 dark:text-gray-300" data-id="{{ $blog->id }}">
                        <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white max-w-xs truncate" title="{{ $blog->blog_title }}">{{ $blog->blog_title }}</td>
                        <td class="px-5 py-4 text-gray-500 dark:text-gray-400">{{ $blog->category ? $blog->category->category_name : 'Uncategorized' }}</td>
                        <td class="px-5 py-4 text-gray-500 dark:text-gray-400">{{ $blog->user ? $blog->user->name : 'Admin' }}</td>
                        <td class="px-5 py-4 font-mono text-gray-500">{{ $blog->post_date ? date('d/m/Y', strtotime($blog->post_date)) : '-' }}</td>
                        <td class="px-5 py-4 text-center">
                            @if($blog->status == '1' || strtolower($blog->status) == 'published')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-bold uppercase tracking-wider bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400">Published</span>
                            @elseif($blog->status == '0' || strtolower($blog->status) == 'draft')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-bold uppercase tracking-wider bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">Draft</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-bold uppercase tracking-wider bg-amber-50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-400">Pending</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="relative inline-block text-left" onclick="event.stopPropagation()">
                                <button onclick="toggleKebab(this)" class="p-1.5 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                                </button>
                                <div class="kebab-menu hidden absolute right-0 mt-1 w-40 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50 py-1">
                                    <a href="/admin/blog/{{ $blog->id }}/edit" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        Edit
                                    </a>
                                    <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                                    <button onclick="openDeleteModal({{ $blog->id }}, '{{ addslashes($blog->blog_title) }}')" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <div class="p-3 bg-gray-50 dark:bg-gray-750 inline-flex rounded-full text-gray-400 mb-3">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white">No articles found</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Try adjusting your filters or search term.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Pagination -->
        <div class="px-5 py-4 border-t border-gray-250 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#f6f6f7] dark:bg-gray-900/10">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                Showing <span class="font-bold text-gray-900 dark:text-white">{{ $blogs->firstItem() ?? 0 }}</span> to <span class="font-bold text-gray-900 dark:text-white">{{ $blogs->lastItem() ?? 0 }}</span> of <span class="font-bold text-gray-900 dark:text-white">{{ $blogs->total() }}</span> entries
            </p>
            @if($blogs->hasPages())
            <nav class="inline-flex items-center gap-1.5" aria-label="Pagination">
                {{-- Previous --}}
                @if($blogs->onFirstPage())
                <span class="flex items-center justify-center p-2 rounded-md border text-xs font-semibold bg-gray-100 dark:bg-gray-800 text-gray-400 border-gray-200 dark:border-gray-700 cursor-not-allowed select-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </span>
                @else
                <a href="{{ $blogs->previousPageUrl() }}" class="flex items-center justify-center p-2 rounded-md border text-xs font-semibold bg-white dark:bg-gray-750 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-655 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </a>
                @endif

                {{-- Page Numbers --}}
                @foreach($blogs->getUrlRange(max(1, $blogs->currentPage()-2), min($blogs->lastPage(), $blogs->currentPage()+2)) as $page => $url)
                    @if($page == $blogs->currentPage())
                    <span class="flex items-center justify-center w-8 h-8 rounded-md border text-xs font-bold bg-[#008060] text-white border-[#008060]">{{ $page }}</span>
                    @else
                    <a href="{{ $url }}" class="flex items-center justify-center w-8 h-8 rounded-md border text-xs font-bold bg-white dark:bg-gray-750 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-655 hover:bg-gray-50 dark:hover:bg-gray-700">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($blogs->hasMorePages())
                <a href="{{ $blogs->nextPageUrl() }}" class="flex items-center justify-center p-2 rounded-md border text-xs font-semibold bg-white dark:bg-gray-750 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-655 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
                @else
                <span class="flex items-center justify-center p-2 rounded-md border text-xs font-semibold bg-gray-100 dark:bg-gray-800 text-gray-400 border-gray-200 dark:border-gray-700 cursor-not-allowed select-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </span>
                @endif
            </nav>
            @endif
        </div>
    </div>

</div>

<!-- Delete Confirm Modal -->
<div id="delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-black dark:bg-opacity-80" onclick="closeDeleteModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 max-w-sm w-full border border-gray-300 dark:border-gray-700 z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-950/30 flex items-center justify-center text-red-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Delete Article</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">This action cannot be undone.</p>
                </div>
            </div>
            <p class="text-sm text-gray-700 dark:text-gray-300 mb-5">Are you sure you want to delete <span id="delete-title" class="font-semibold"></span>?</p>
            <div class="flex gap-3 justify-end">
                <button onclick="closeDeleteModal()" class="px-4 py-2 text-sm font-semibold rounded-md border border-gray-300 dark:border-gray-650 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-750 hover:bg-gray-50 cursor-pointer">Cancel</button>
                <button onclick="confirmDelete()" class="px-4 py-2 text-sm font-semibold rounded-md text-white bg-red-600 hover:bg-red-700 cursor-pointer">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast" class="fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm">
    <div class="rounded-full p-1 bg-green-500 text-white">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    </div>
    <span id="toast-message" class="text-sm font-semibold">Done!</span>
</div>

<script>
    let deleteTarget = null;

    // Search: debounce submit on keyup
    const searchInput = document.getElementById("blog-search");
    if (searchInput) {
        let debounceTimer;
        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => searchInput.closest('form').submit(), 450);
        });
    }

    function openDeleteModal(id, title) {
        deleteTarget = id;
        document.getElementById("delete-title").innerText = `"${title}"`;
        document.getElementById("delete-modal").classList.remove("hidden");
    }

    function closeDeleteModal() {
        document.getElementById("delete-modal").classList.add("hidden");
        deleteTarget = null;
    }

    async function confirmDelete() {
        if (deleteTarget !== null) {
            try {
                const response = await fetch(`/admin/blog/${deleteTarget}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                });
                const data = await response.json();
                if (data.success) {
                    closeDeleteModal();
                    // Remove row from DOM
                    const row = document.querySelector(`tr[data-id="${deleteTarget}"]`);
                    if (row) {
                        row.remove();
                    }
                    showToast("Article deleted successfully!");
                }
            } catch (error) {
                alert("Error deleting article.");
            }
        }
    }

    function showToast(msg) {
        const t = document.getElementById("toast");
        document.getElementById("toast-message").innerText = msg;
        t.className = "fixed bottom-5 right-5 z-50 transform translate-y-0 opacity-100 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm";
        setTimeout(() => { t.className = "fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm"; }, 3500);
    }

    function toggleKebab(btn) {
        const menu = btn.nextElementSibling;
        const isOpen = !menu.classList.contains('hidden');
        document.querySelectorAll('.kebab-menu').forEach(m => {
            m.classList.add('hidden');
            m.style.position = '';
            m.style.top = '';
            m.style.left = '';
        });
        
        if (!isOpen) {
            menu.classList.remove('hidden');
            const rect = btn.getBoundingClientRect();
            menu.style.position = 'fixed';
            menu.style.top = (rect.bottom + 4) + 'px';
            menu.style.left = (rect.right - 160) + 'px'; // 160px is w-40
        }
    }
    
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.kebab-menu') && !e.target.closest('[onclick*="toggleKebab"]')) {
            document.querySelectorAll('.kebab-menu').forEach(m => m.classList.add('hidden'));
        }
    });

    // Hide dropdown on any scroll event to prevent floating menus
    document.addEventListener('scroll', function(e) {
        if (!e.target.closest('.kebab-menu')) {
            document.querySelectorAll('.kebab-menu').forEach(m => m.classList.add('hidden'));
        }
    }, true);
</script>
@endsection
