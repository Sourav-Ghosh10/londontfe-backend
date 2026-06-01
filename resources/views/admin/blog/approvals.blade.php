@extends('admin.layout')

@section('content')
<div class="w-full">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-1.5 text-xxs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1.5">
                <a href="/admin" class="hover:text-gray-600 dark:hover:text-gray-300">Admin</a>
                <span>&rsaquo;</span>
                <a href="/admin/blog" class="hover:text-gray-600 dark:hover:text-gray-300">Blog</a>
                <span>&rsaquo;</span>
                <span class="text-[#008060] font-extrabold">Approval List</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Approval List</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Review and approve articles pending publication.</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xxs font-bold text-gray-700 dark:text-gray-400 bg-[#f6f6f7] dark:bg-gray-900/40 uppercase border-b border-gray-250 dark:border-gray-700">
                    <tr>
                        <th class="px-5 py-4">Title</th>
                        <th class="px-5 py-4">Category</th>
                        <th class="px-5 py-4">Author</th>
                        <th class="px-5 py-4">Submitted</th>
                        <th class="px-5 py-4 text-right w-48">Actions</th>
                    </tr>
                </thead>
                <tbody id="approval-tbody" class="divide-y divide-gray-200 dark:divide-gray-700"></tbody>
            </table>
        </div>
        <div id="empty-state" class="hidden py-16 text-center">
            <div class="p-3 bg-emerald-50 dark:bg-emerald-950/20 inline-flex rounded-full text-emerald-500 mb-3">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">All caught up!</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">No articles pending approval.</p>
        </div>
        <div class="px-5 py-4 border-t border-gray-250 dark:border-gray-700 flex items-center justify-between bg-[#f6f6f7] dark:bg-gray-900/10">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400" id="approval-summary">0 pending</p>
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
    document.addEventListener("DOMContentLoaded", () => {
        let articles = [];
        try { articles = JSON.parse(localStorage.getItem("londontfe_blog_articles") || "[]"); } catch(e) {}
        const pending = articles.filter(a => a.status === "Pending");
        renderTable(pending, articles);
    });

    function renderTable(pending, allArticles) {
        const tbody = document.getElementById("approval-tbody");
        const empty = document.getElementById("empty-state");
        const summary = document.getElementById("approval-summary");
        tbody.innerHTML = "";

        if (pending.length === 0) {
            empty.classList.remove("hidden");
            summary.innerText = "0 pending";
            return;
        }
        empty.classList.add("hidden");
        summary.innerText = `${pending.length} pending approval`;

        pending.forEach(item => {
            const tr = document.createElement("tr");
            tr.className = "hover:bg-gray-50/50 dark:hover:bg-gray-900/10 transition-colors text-xs text-gray-800 dark:text-gray-300";
            tr.innerHTML = `
                <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white max-w-xs truncate" title="${item.title}">${item.title}</td>
                <td class="px-5 py-4 text-gray-500 dark:text-gray-400">${item.category}</td>
                <td class="px-5 py-4 text-gray-500 dark:text-gray-400">${item.author}</td>
                <td class="px-5 py-4 font-mono text-gray-500">${item.date}</td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <button onclick="approveArticle(${item.id})" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xxs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 rounded-md border border-emerald-200 dark:border-emerald-900/40 transition-colors cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Approve
                        </button>
                        <button onclick="rejectArticle(${item.id})" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xxs font-bold uppercase tracking-wider text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/20 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-md border border-red-200 dark:border-red-900/40 transition-colors cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            Reject
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function approveArticle(id) {
        updateStatus(id, "Published", "Article approved and published!");
    }

    function rejectArticle(id) {
        updateStatus(id, "Draft", "Article rejected and moved to Draft.");
    }

    function updateStatus(id, newStatus, msg) {
        let articles = [];
        try { articles = JSON.parse(localStorage.getItem("londontfe_blog_articles") || "[]"); } catch(e) {}
        const idx = articles.findIndex(a => a.id === id);
        if (idx !== -1) articles[idx].status = newStatus;
        localStorage.setItem("londontfe_blog_articles", JSON.stringify(articles));
        const pending = articles.filter(a => a.status === "Pending");
        renderTable(pending, articles);
        showToast(msg);
    }

    function showToast(msg) {
        const t = document.getElementById("toast");
        document.getElementById("toast-message").innerText = msg;
        t.className = "fixed bottom-5 right-5 z-50 transform translate-y-0 opacity-100 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm";
        setTimeout(() => { t.className = "fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm"; }, 3500);
    }
</script>
@endsection
