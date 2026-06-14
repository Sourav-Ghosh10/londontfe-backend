@extends('admin.layout')

@section('content')
<div class="w-full">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-1.5 text-xxs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1.5">
                <a href="/admin" class="hover:text-gray-600 dark:hover:text-gray-300">Admin</a>
                <span>&rsaquo;</span>
                <a href="/admin/website/testimonials" class="hover:text-gray-600 dark:hover:text-gray-300">Testimonials</a>
                <span>&rsaquo;</span>
                <span class="text-[#008060] font-extrabold">Add Testimonial</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add Testimonial</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Add a new customer testimonial to display on the website.</p>
        </div>
        <a href="/admin/website/testimonials" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 px-4 py-2.5 rounded-md border border-gray-300 dark:border-gray-650 transition-all">
            &larr; Back
        </a>
    </div>

    <form onsubmit="handleSave(event)">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left: Main Fields -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-5">Testimonial Details</h2>
                    <div class="space-y-5">

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Author Name <span class="text-red-500">*</span></label>
                            <input type="text" id="author-name" required placeholder="e.g. Peter W."
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Testimonial <span class="text-red-500">*</span></label>
                            <textarea id="testimonial-text" required rows="5" placeholder="Enter the customer testimonial..."
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors resize-none"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Author Info <span class="text-red-500">*</span></label>
                            <textarea id="author-info" required rows="3" placeholder="e.g. Senior Manager, London — or job title, company, location"
                                class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors resize-none"></textarea>
                            <p class="text-xxs text-gray-400 dark:text-gray-500 mt-1">Job title, company name, or location of the author.</p>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right: Sidebar -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Publish</h2>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Status</label>
                        <div class="relative">
                            <select id="testimonial-status" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] appearance-none cursor-pointer">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3 pt-5 border-t border-gray-200 dark:border-gray-700 mt-5">
                        <a href="/admin/website/testimonials" class="flex-1 text-center px-4 py-2.5 text-sm font-semibold rounded-md border border-gray-300 dark:border-gray-650 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-750 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</a>
                        <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] rounded-md transition-colors cursor-pointer">Save</button>
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

<script>
    function handleSave(e) {
        e.preventDefault();
        const authorName        = document.getElementById('author-name').value.trim();
        const testimonialText   = document.getElementById('testimonial-text').value.trim();
        const authorDescription = document.getElementById('author-info').value.trim();
        const status            = document.getElementById('testimonial-status').value;

        if (!authorName)        { alert('Author Name is required.'); return; }
        if (!testimonialText)   { alert('Testimonial text is required.'); return; }
        if (!authorDescription) { alert('Author Info is required.'); return; }

        const formData = new FormData();
        formData.append('author_name', authorName);
        formData.append('testimonial_text', testimonialText);
        formData.append('author_description', authorDescription);
        formData.append('status', status);

        fetch('/admin/website/testimonials', {
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
                showToast('Testimonial saved successfully!');
                setTimeout(() => { window.location.href = '/admin/website/testimonials'; }, 1000);
            } else {
                alert(data.error || 'Failed to save testimonial.');
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
        setTimeout(() => { t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm'; }, 3500);
    }
</script>
@endsection
