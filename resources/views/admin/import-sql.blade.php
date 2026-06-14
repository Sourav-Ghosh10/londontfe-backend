@extends('admin.layout')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <!-- Page header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-slate-800 dark:text-slate-100 font-bold">Import Database (SQL) ✨</h1>
        </div>
    </div>

    <!-- Feedback messages -->
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-100 text-emerald-600 border border-emerald-200 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 px-4 py-3 bg-rose-100 text-rose-600 border border-rose-200 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700 p-6">
        <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Upload SQL File</h2>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">
            If you do not have direct access to the live database, you can use this form to upload and execute a <code>.sql</code> file. This will create the user table and insert the imported data automatically.
        </p>

        <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1" for="sql_file">SQL File (.sql) <span class="text-rose-500">*</span></label>
                    <input id="sql_file" name="sql_file" type="file" accept=".sql" class="form-input w-full md:w-1/2" required />
                    @error('sql_file')
                        <div class="text-xs mt-1 text-rose-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex items-center mt-6">
                    <button type="submit" class="btn bg-indigo-500 hover:bg-indigo-600 text-white" onclick="return confirm('Are you sure? This will execute all queries in the uploaded file directly on the database.')">
                        Upload & Execute
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection
