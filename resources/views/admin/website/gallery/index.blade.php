@extends('admin.layout')

@section('content')
<div class="w-full">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-1.5 text-xxs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1.5">
                <a href="/admin" class="hover:text-gray-600 dark:hover:text-gray-300">Admin</a>
                <span>&rsaquo;</span>
                <span class="text-gray-600 dark:text-gray-300">Website</span>
                <span>&rsaquo;</span>
                <span class="text-[#008060] font-extrabold">Media</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Media Library</h1>
        </div>
        <a href="/admin/website/gallery/create" class="inline-flex items-center justify-center text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] px-5 py-2.5 rounded-md transition-all shadow-xs focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] whitespace-nowrap">
            + Add Media
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 overflow-hidden">

        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-end gap-4 bg-gray-50 dark:bg-gray-800/80">
            <div class="flex items-center gap-2 w-full md:w-auto">
                <span class="text-sm text-gray-600 dark:text-gray-400">Search:</span>
                <input type="text" id="gallery-search" oninput="filterGallery()" class="w-full md:w-64 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-[#008060]">
                <button onclick="clearSearch()" class="px-3 py-1.5 text-sm font-semibold rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Clear</button>
            </div>
        </div>

        <div class="p-6 bg-gray-50/50 dark:bg-gray-900/20">
            <div id="gallery-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6"></div>
        </div>

        <div id="empty-state" class="hidden py-16 text-center">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">No media found</h3>
        </div>

        <div class="px-5 py-6 border-t border-gray-250 dark:border-gray-700 flex flex-col items-center justify-center gap-3 bg-[#f6f6f7] dark:bg-gray-900/10">
            <button type="button" id="load-more-btn" onclick="loadMore()" class="px-6 py-2.5 text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] rounded-md transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060]">
                Load More
            </button>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400" id="table-summary">
                Displaying <span class="font-bold text-gray-900 dark:text-white">0</span> of <span class="font-bold text-gray-900 dark:text-white">0</span> items
            </p>
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

@php
    $galleryData = $galleries->map(function($g) {
        return [
            'id' => $g->id,
            'type' => $g->media_type,
            'image' => \Illuminate\Support\Facades\Storage::disk('s3')->url($g->file_path),
            'title' => $g->media_title ?? 'Untitled'
        ];
    });
@endphp
<script>
    let gallery = [], filtered = [], visibleCount = 10, itemsPerLoad = 10, sortCol = '', sortAsc = true;

    document.addEventListener('DOMContentLoaded', () => {
        gallery = @json($galleryData);
        filterGallery();
    });

    // Remove localStorage save function
    function save() {}

    function filterGallery() {
        const q = document.getElementById('gallery-search').value.toLowerCase().trim();
        filtered = gallery.filter(c => c.title.toLowerCase().includes(q));
        if (sortCol) filtered.sort((a, b) => {
            let A = String(a[sortCol]).toLowerCase(), B = String(b[sortCol]).toLowerCase();
            return sortAsc ? A.localeCompare(B) : B.localeCompare(A);
        });
        visibleCount = itemsPerLoad; 
        renderTable();
    }

    function sortTable(col) { sortCol === col ? sortAsc = !sortAsc : (sortCol = col, sortAsc = true); filterGallery(); }
    function clearSearch() { document.getElementById('gallery-search').value = ''; filterGallery(); }
    function loadMore() { visibleCount += itemsPerLoad; renderTable(); }

    function renderTable() {
        const grid = document.getElementById('gallery-grid');
        const empty = document.getElementById('empty-state');
        const summary = document.getElementById('table-summary');
        const loadMoreBtn = document.getElementById('load-more-btn');

        grid.innerHTML = '';
        if (filtered.length === 0) { 
            grid.classList.add('hidden'); 
            empty.classList.remove('hidden'); 
            summary.innerHTML = `Displaying <b>0</b> of <b>0</b> items`; 
            loadMoreBtn.classList.add('hidden');
            return; 
        }
        
        grid.classList.remove('hidden'); 
        empty.classList.add('hidden');

        const total = filtered.length;
        const end = Math.min(visibleCount, total);

        filtered.slice(0, end).forEach(item => {
            const card = document.createElement('div');
            card.className = 'group relative bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300';
            
            const imageHtml = item.image
                ? `<img src="${item.image}" alt="${item.title}" class="w-full h-48 object-cover object-center group-hover:scale-105 transition-transform duration-500 ease-in-out">`
                : `<div class="w-full h-48 bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 text-sm font-medium">No Media</div>`;

            const typeColor = item.type === 'Video' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' : (item.type === 'Document' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400');
            const typeBadge = `<span class="absolute top-2 left-2 px-2 py-1 text-[10px] font-bold uppercase tracking-wider rounded ${typeColor} shadow-sm backdrop-blur-md bg-opacity-90">${item.type || 'Image'}</span>`;

            card.innerHTML = `
                <div class="relative w-full overflow-hidden bg-gray-100 dark:bg-gray-700 border-b border-gray-100 dark:border-gray-700">
                    ${imageHtml}
                    ${typeBadge}
                    <!-- Absolute Actions overlay -->
                    <div class="absolute top-2 right-2">
                        <div class="relative inline-block text-left" onclick="event.stopPropagation()">
                            <button onclick="toggleKebab(this)" class="p-1.5 text-gray-700 bg-white/90 hover:bg-white dark:text-gray-200 dark:bg-gray-800/90 dark:hover:bg-gray-800 rounded-lg shadow-sm backdrop-blur-sm transition-colors focus:outline-none ring-1 ring-black/5 dark:ring-white/10">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                            </button>
                            <div class="kebab-menu hidden absolute right-0 mt-1 w-36 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50 py-1 origin-top-right transition-all">
                                <button onclick="copyToClipboard('${item.image}')" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    Copy URL
                                </button>
                                <button class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    Edit
                                </button>
                                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                                <button onclick="deleteGalleryItem(${item.id})" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-800/50">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white truncate" title="${item.title}">${item.title}</h3>
                </div>
            `;
            grid.appendChild(card);
        });
        
        summary.innerHTML = `Displaying <span class="font-bold text-gray-900 dark:text-white">${end}</span> of <span class="font-bold text-gray-900 dark:text-white">${total}</span> media items`;
        
        if (visibleCount >= total) {
            loadMoreBtn.classList.add('hidden');
        } else {
            loadMoreBtn.classList.remove('hidden');
        }
    }



    function deleteGalleryItem(id) {
        if (confirm('Delete this media item?')) {
            fetch(`/admin/website/gallery/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => {
                gallery = gallery.filter(c => c.id !== id); filterGallery();
                const t = document.getElementById('toast'); document.getElementById('toast-message').innerText = 'Media deleted!';
                t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-0 opacity-100 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm';
                setTimeout(() => { t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm'; }, 3000);
            });
        }
    }

    function toggleKebab(btn) {
        const menu = btn.nextElementSibling;
        const isOpen = !menu.classList.contains('hidden');
        document.querySelectorAll('.kebab-menu').forEach(m => m.classList.add('hidden'));
        if (!isOpen) menu.classList.remove('hidden');
    }

    function copyToClipboard(url) {
        navigator.clipboard.writeText(url).then(() => {
            const t = document.getElementById('toast'); 
            document.getElementById('toast-message').innerText = 'URL copied!';
            t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-0 opacity-100 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm';
            setTimeout(() => { t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm'; }, 2000);
        }).catch(err => {
            console.error('Failed to copy text: ', err);
        });
        document.querySelectorAll('.kebab-menu').forEach(m => m.classList.add('hidden'));
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.kebab-menu') && !e.target.closest('[onclick*="toggleKebab"]')) {
            document.querySelectorAll('.kebab-menu').forEach(m => m.classList.add('hidden'));
        }
    });
</script>
@endsection
