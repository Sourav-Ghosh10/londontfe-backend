<div class="flex flex-wrap gap-1 mb-2 p-2 bg-gray-50 dark:bg-gray-700/50 rounded-md border border-gray-200 dark:border-gray-600">
    <button type="button" onclick="execCmd('bold')" title="Bold" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-bold text-xs transition-colors w-7 h-7 flex items-center justify-center">B</button>
    <button type="button" onclick="execCmd('italic')" title="Italic" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 italic text-xs transition-colors w-7 h-7 flex items-center justify-center">I</button>
    <button type="button" onclick="execCmd('underline')" title="Underline" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 underline text-xs transition-colors w-7 h-7 flex items-center justify-center">U</button>
    <div class="w-px h-5 bg-gray-300 dark:bg-gray-600 mx-0.5 self-center"></div>
    <button type="button" onclick="execCmd('insertUnorderedList')" title="Bullet list" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-400 transition-colors w-7 h-7 flex items-center justify-center">
        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 5a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V6a1 1 0 011-1zm-3 4a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V10a1 1 0 011-1zm-3 4a1 1 0 100 2 1 1 0 000-2zm3 0a1 1 0 011 1v.01a1 1 0 01-2 0V14a1 1 0 011-1z"/><path d="M9 6h7a1 1 0 010 2H9a1 1 0 110-2zm0 4h7a1 1 0 010 2H9a1 1 0 110-2zm0 4h7a1 1 0 010 2H9a1 1 0 110-2z"/></svg>
    </button>
    <button type="button" onclick="execCmd('insertOrderedList')" title="Numbered list" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-400 transition-colors w-7 h-7 flex items-center justify-center">
        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4h1v3H3V4zm1 9H3v1h1v-1zm-1-4h1v1H3V9zm4-5h9v2H7V4zm0 6h9v2H7v-2zm0 6h9v2H7v-2zM3 13v1h1v-1H3z"/></svg>
    </button>
    <div class="w-px h-5 bg-gray-300 dark:bg-gray-600 mx-0.5 self-center"></div>
    <button type="button" onclick="execCmd('justifyLeft')" title="Align left" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-400 transition-colors w-7 h-7 flex items-center justify-center">
        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 4h16v2H2V4zm0 4h10v2H2V8zm0 4h16v2H2v-2zm0 4h10v2H2v-2z"/></svg>
    </button>
    <button type="button" onclick="execCmd('justifyCenter')" title="Center" class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-400 transition-colors w-7 h-7 flex items-center justify-center">
        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 4h16v2H2V4zm3 4h10v2H5V8zm-3 4h16v2H2v-2zm3 4h10v2H5v-2z"/></svg>
    </button>
    <div class="w-px h-5 bg-gray-300 dark:bg-gray-600 mx-0.5 self-center"></div>
    <button type="button" onclick="toggleSourceCode(this)" title="Source Code" class="source-code-btn p-1.5 rounded hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-400 transition-colors w-7 h-7 flex items-center justify-center font-mono font-bold text-xs">
        &lt;&gt;
    </button>
</div>
