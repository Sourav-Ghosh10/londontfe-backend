<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UserImportController extends Controller
{
    public function import(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'sql_file' => 'required|file|mimes:sql,txt',
            ]);

            try {
                $sqlPath = $request->file('sql_file')->getRealPath();
                $sqlContent = File::get($sqlPath);
                
                // Execute the raw SQL file
                DB::unprepared($sqlContent);

                return back()->with('success', 'SQL file executed successfully! Tables and data have been imported.');
            } catch (\Exception $e) {
                return back()->with('error', 'Error executing SQL: ' . $e->getMessage());
            }
        }

        // Simple inline view for the form
        return view('admin.import-sql');
    }
}
