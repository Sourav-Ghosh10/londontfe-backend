@extends('admin.layout')

@section('content')
<div class="w-full max-w-4xl mx-auto">

    <div class="flex items-center gap-1.5 text-xxs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">
        <a href="/admin" class="hover:text-gray-600 dark:hover:text-gray-300">Admin</a>
        <span>&rsaquo;</span>
        <span class="text-gray-600 dark:text-gray-300">Website</span>
        <span>&rsaquo;</span>
        <a href="/admin/website/gallery" class="hover:text-gray-600 dark:hover:text-gray-300">Media</a>
        <span>&rsaquo;</span>
        <span class="text-[#008060] font-extrabold">Add New</span>
    </div>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add Media Item</h1>
        <a href="/admin/website/gallery" class="text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
            Cancel
        </a>
    </div>

    @if(session('error'))
        <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-800" role="alert">
            <span class="font-medium">Error!</span> {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-800" role="alert">
            <span class="font-medium">Please fix the following errors:</span>
            <ul class="list-disc pl-5 mt-1.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-250 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-base font-bold text-gray-900 dark:text-white">Media Details</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Upload and configure a new media item for the website.</p>
        </div>

        <form action="/admin/website/gallery" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Media Type</label>
                        <select name="media_type" class="w-full text-sm bg-gray-50 dark:bg-gray-750 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                            <option value="Image">Image</option>
                            <option value="Video">Video</option>
                            <option value="Document">Document (PDF/Word)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Media Title</label>
                        <input type="text" name="media_title" placeholder="e.g. Leadership Workshop 2023" class="w-full text-sm bg-gray-50 dark:bg-gray-750 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                    </div>
                </div>

                <!-- Alt Text -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Alt Text</label>
                    <input type="text" name="alt_text" placeholder="e.g. A group of people in a meeting" class="w-full text-sm bg-gray-50 dark:bg-gray-750 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 rounded-md px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#008060] focus:border-[#008060] transition-colors">
                </div>

                <!-- Media Upload -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Upload Media</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-[#008060] dark:hover:border-[#008060] transition-colors cursor-pointer bg-gray-50 dark:bg-gray-900/20">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center mt-2">
                                <label for="file-upload" class="relative cursor-pointer bg-transparent rounded-md font-medium text-[#008060] hover:text-[#006e52] focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[#008060]">
                                    <span id="file-upload-name">Upload a file</span>
                                    <input id="file-upload" name="media_file" type="file" class="sr-only" required onchange="document.getElementById('file-upload-name').innerText = this.files[0] ? this.files[0].name : 'Upload a file';">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-500">PNG, JPG, GIF, MP4, PDF up to 10MB</p>
                        </div>
                    </div>
                </div>



            </div>

        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/40 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
            <a href="/admin/website/gallery" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#008060] border border-transparent rounded-md shadow-sm hover:bg-[#006e52] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#008060] transition-colors">
                Save Media
            </button>
        </div>
        </form>
    </div>
</div>

<style>
    .dark .bg-gray-750 { background-color: #2a2e35; }
</style>
@endsection
