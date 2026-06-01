@extends('admin.layout')

@section('content')
<div class="w-full">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-1.5 text-xxs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1.5">
                <a href="/admin" class="hover:text-gray-600 dark:hover:text-gray-300">Admin</a>
                <span>&rsaquo;</span>
                <a href="/admin/website/autoreply" class="hover:text-gray-600 dark:hover:text-gray-300">Email Autoreply</a>
                <span>&rsaquo;</span>
                <span class="text-[#008060] font-extrabold">Edit Autoreply</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Auto Response Content</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Update the automatic email response for this form.</p>
        </div>
        <a href="/admin/website/autoreply" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 px-4 py-2.5 rounded-md border border-gray-300 dark:border-gray-650 transition-all">
            &larr; Back to List
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left: Main Fields -->
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-5">Autoresponse Details</h2>
                <div class="space-y-5">

                    <!-- Form Name -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Form Name <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select id="form-name" required class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors appearance-none cursor-pointer">
                                <option value="">— Select Form —</option>
                                <option value="Contact Us">Contact Us</option>
                                <option value="Admin - Pay Later mail">Admin - Pay Later mail</option>
                                <option value="Registration">Registration</option>
                                <option value="Subscribe">Subscribe</option>
                                <option value="Quote">Quote</option>
                                <option value="Event">Event</option>
                                <option value="Enquire">Enquire</option>
                                <option value="Brochure">Brochure</option>
                                <option value="Course Registration">Course Registration</option>
                                <option value="Certificate Validation">Certificate Validation</option>
                                <option value="Pretraining">Pretraining</option>
                                <option value="Outline Fail">Outline Fail</option>
                                <option value="Location PDF">Location PDF</option>
                                <option value="Event Capture">Event Capture</option>
                                <option value="Course Outline">Course Outline</option>
                                <option value="Directory Document Download">Directory Document Download</option>
                                <option value="Course Callus">Course Callus</option>
                                <option value="Webinar Courses">Webinar Courses</option>
                                <option value="Pay Later">Pay Later</option>
                                <option value="Custom Payment">Custom Payment</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Mail Subject -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Mail Subject <span class="text-red-500">*</span></label>
                        <input type="text" id="mail-subject" required placeholder="e.g. Your support request has been received"
                            class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                    </div>

                    <!-- Mail Preview -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Mail Preview</label>
                        <input type="text" id="mail-preview" placeholder="e.g. Thank you for your support request, you will shortly hear back from us."
                            class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                        <p class="text-xxs text-gray-400 dark:text-gray-500 mt-1">Short preview text shown in the email inbox before opening.</p>
                    </div>

                    <!-- Mail Content (Quill) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Mail Content <span class="text-red-500">*</span></label>
                        <div id="mail-content-editor" style="min-height:320px;"></div>
                    </div>

                    <!-- Default Content -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Default Content</label>
                        <textarea id="default-content" rows="3" placeholder="e.g. Dear Test, Thank you for contacting London Training for Excellence..."
                            class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] focus:border-[#008060] transition-colors resize-none"></textarea>
                        <p class="text-xxs text-gray-400 dark:text-gray-500 mt-1">Fallback plain text shown if the HTML email cannot be rendered.</p>
                    </div>

                </div>
            </div>

        </div>

        <!-- Right: Sidebar -->
        <div class="space-y-6">

            <!-- Publish -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 p-6">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Publish</h2>
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1.5">Content Status</label>
                    <div class="relative">
                        <select id="content-status" class="w-full text-sm bg-[#f6f6f7] dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#008060] appearance-none cursor-pointer">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-2 pt-5 border-t border-gray-200 dark:border-gray-700 mt-5">
                    <button onclick="handleUpdate(false)" type="button" class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-[#008060] hover:bg-[#006e52] rounded-md transition-colors cursor-pointer">Update Changes</button>
                    <button onclick="handleUpdate(true)" type="button" class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-gray-700 hover:bg-gray-800 dark:bg-gray-600 dark:hover:bg-gray-500 rounded-md transition-colors cursor-pointer">Update &amp; Go Back</button>
                    <a href="/admin/website/autoreply" class="w-full text-center px-4 py-2.5 text-sm font-semibold rounded-md border border-gray-300 dark:border-gray-650 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-750 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</a>
                </div>
            </div>

            <!-- Help Card -->
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-700/50 p-5">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-800 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-blue-800 dark:text-blue-200 mb-1">Email Variables</h3>
                        <p class="text-xxs text-blue-600 dark:text-blue-300 leading-relaxed">Use <code class="bg-blue-100 dark:bg-blue-800 px-1 rounded">@{{name}}</code> for recipient name, <code class="bg-blue-100 dark:bg-blue-800 px-1 rounded">@{{email}}</code> for their email, and <code class="bg-blue-100 dark:bg-blue-800 px-1 rounded">@{{course}}</code> for course name.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast" class="fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm">
    <div class="rounded-full p-1 bg-green-500 text-white">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    </div>
    <span id="toast-message" class="text-sm font-semibold">Updated!</span>
</div>

<!-- Quill CDN -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<style>
    #mail-content-editor { background: #f6f6f7; border-radius: 0 0 0.375rem 0.375rem; font-size: 0.875rem; }
    .ql-toolbar.ql-snow { background: #fff; border-radius: 0.375rem 0.375rem 0 0; border-color: #d1d5db; }
    .ql-container.ql-snow { border-color: #d1d5db; border-radius: 0 0 0.375rem 0.375rem; }
    .dark #mail-content-editor { background: #374151; color: #e5e7eb; }
    .dark .ql-toolbar.ql-snow { background: #1f2937; border-color: #4b5563; }
    .dark .ql-container.ql-snow { border-color: #4b5563; }
    .dark .ql-toolbar .ql-stroke { stroke: #9ca3af; }
    .dark .ql-toolbar .ql-fill { fill: #9ca3af; }
    .dark .ql-toolbar .ql-picker { color: #9ca3af; }
    .dark .ql-editor.ql-blank::before { color: #6b7280; }
</style>

<script>
    // Get id from URL path e.g. /admin/website/autoreply/3/edit
    const pathParts = window.location.pathname.split('/');
    const editId = parseInt(pathParts[pathParts.indexOf('autoreply') + 1]) || 1;

    let currentItem = null;

    document.addEventListener('DOMContentLoaded', () => {
        // Load item
        let items = [];
        try { items = JSON.parse(localStorage.getItem('londontfe_autoreply') || '[]'); } catch(e) {}
        currentItem = items.find(i => i.id === editId);

        if (currentItem) {
            document.getElementById('form-name').value     = currentItem.formName || '';
            document.getElementById('mail-subject').value  = currentItem.mailSubject || '';
            document.getElementById('mail-preview').value  = currentItem.mailPreview || '';
            document.getElementById('default-content').value = currentItem.defaultContent || '';
            document.getElementById('content-status').value  = currentItem.status || 'Active';
        }

        // Init Quill
        window.quillEditor = new Quill('#mail-content-editor', {
            theme: 'snow',
            placeholder: 'Write the full email body here...',
            modules: {
                toolbar: [
                    [{ 'font': [] }, { 'size': ['small', false, 'large', 'huge'] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'indent': '-1' }, { 'indent': '+1' }],
                    ['link', 'image'],
                    ['blockquote', 'code-block'],
                    ['clean']
                ]
            }
        });

        if (currentItem && currentItem.mailContent) {
            window.quillEditor.root.innerHTML = currentItem.mailContent;
        }
    });

    function handleUpdate(goBack) {
        const formName     = document.getElementById('form-name').value.trim();
        const mailSubject  = document.getElementById('mail-subject').value.trim();
        const mailPreview  = document.getElementById('mail-preview').value.trim();
        const defaultContent = document.getElementById('default-content').value.trim();
        const status       = document.getElementById('content-status').value;
        const mailContent  = window.quillEditor ? window.quillEditor.root.innerHTML : '';

        if (!formName)    { alert('Form Name is required.'); return; }
        if (!mailSubject) { alert('Mail Subject is required.'); return; }

        let items = [];
        try { items = JSON.parse(localStorage.getItem('londontfe_autoreply') || '[]'); } catch(e) {}
        const idx = items.findIndex(i => i.id === editId);
        if (idx !== -1) {
            items[idx] = { ...items[idx], formName, mailSubject, mailPreview, mailContent, defaultContent, status };
        } else {
            items.push({ id: editId, formName, mailSubject, mailPreview, mailContent, defaultContent, status });
        }
        localStorage.setItem('londontfe_autoreply', JSON.stringify(items));

        showToast('Autoreply updated successfully!');
        if (goBack) { setTimeout(() => { window.location.href = '/admin/website/autoreply'; }, 900); }
    }

    function showToast(msg) {
        const t = document.getElementById('toast');
        document.getElementById('toast-message').innerText = msg;
        t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-0 opacity-100 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm';
        setTimeout(() => { t.className = 'fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-3 rounded-lg shadow-xl max-w-sm'; }, 3500);
    }
</script>
@endsection
